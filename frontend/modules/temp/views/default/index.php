<?php

use yii\web\JsExpression;

$this->title = 'Документы';

?>

<div class="container">
    <div class="col-md-3">

        <?= \wbraganca\fancytree\FancytreeWidget::widget([
            'options' => [
                'source' => $data,
                'activate' => new JsExpression('function(event, data) {
                    console.log(data.node.key);
                    if (data.node.key.substr(0,1) == "i") {
                        getInfo(data.node.key.substr(2));
                    }
                    if (data.node.key.substr(0,1) == "d") {
                        getDoc(data.node.key.substr(2));
                    }
                    if (data.node.key.substr(0,1) == "emp") {
                        if ($("#employee-user_id").val() != "") {
                            $("#employee-user_id").select2("val", "");
                        }
                        if ($("#list").data("\'"+data.node.key+"\'") == undefined){
                            $("#list").data("\'"+data.node.key+"\'", true);
                            getEmployeeInfo(data.node.key.substr(4, data.node.key.length-4));
                            console.log("::"+data.node.key);
                        } else {
                            var key = "profile-"+data.node.key.substr(4, data.node.key.length-4);
                            $("#"+key).show();
                            $("#title-"+key).show();
                        }
                    }
                }'),
            ]
        ]); ?>
    </div>

    <div class="col-md-9">
        <?php

        class nokogiri implements IteratorAggregate
        {
            const
                regexp =
                "/(?P<tag>[a-z0-9]+)?(\[(?P<attr>\S+)(=(?P<value>[^\]]+))?\])?(#(?P<id>[^\s:>#\.]+))?(\.(?P<class>[^\s:>#\.]+))?(:(?P<pseudo>(first|last|nth)-child)(\((?P<expr>[^\)]+)\))?)?\s*(?P<rel>>)?/isS";
            protected $_source = '';
            /**
             * @var DOMDocument
             */
            protected $_dom = null;
            /**
             * @var DOMDocument
             */
            protected $_tempDom = null;
            /**
             * @var DOMXpath
             * */
            protected $_xpath = null;
            /**
             * @var libxmlErrors
             */
            protected $_libxmlErrors = null;
            protected static $_compiledXpath = array();

            public function __construct($htmlString = '')
            {
                $this->loadHtml($htmlString);
            }

            public function getRegexp()
            {
                $tag = "(?P<tag>[a-z0-9]+)?";
                $attr = "(\[(?P<attr>\S+)=(?P<value>[^\]]+)\])?";
                $id = "(#(?P<id>[^\s:>#\.]+))?";
                $class = "(\.(?P<class>[^\s:>#\.]+))?";
                $child = "(first|last|nth)-child";
                $expr = "(\((?P<expr>[^\)]+)\))";
                $pseudo = "(:(?P<pseudo>" . $child . ")" . $expr . "?)?";
                $rel = "\s*(?P<rel>>)?";
                $regexp = "/" . $tag . $attr . $id . $class . $pseudo . $rel . "/isS";
                return $regexp;
            }

            public static function fromHtml($htmlString)
            {
                $me = new self();
                $me->loadHtml($htmlString);
                return $me;
            }

            public static function fromHtmlNoCharset($htmlString)
            {
                $me = new self();
                $me->loadHtmlNoCharset($htmlString);
                return $me;
            }

            public static function fromDom($dom)
            {
                $me = new self();
                $me->loadDom($dom);
                return $me;
            }

            public function loadDom($dom)
            {
                $this->_dom = $dom;
            }

            public function loadHtmlNoCharset($htmlString = '')
            {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                if (strlen($htmlString)) {
                    libxml_use_internal_errors(true);
                    $this->_libxmlErrors = null;
                    $dom->loadHTML('<?xml encoding="UTF-8">' . $htmlString);
                    // dirty fix
                    foreach ($dom->childNodes as $item) {
                        if ($item->nodeType == XML_PI_NODE) {
                            $dom->removeChild($item); // remove hack
                            break;
                        }
                    }
                    $dom->encoding = 'UTF-8'; // insert proper
                    $this->_libxmlErrors = libxml_get_errors();
                    libxml_clear_errors();
                }
                $this->loadDom($dom);
            }

            public function loadHtml($htmlString = '')
            {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                if (strlen($htmlString)) {
                    libxml_use_internal_errors(true);
                    $this->_libxmlErrors = null;
                    $dom->loadHTML($htmlString);
                    $this->_libxmlErrors = libxml_get_errors();
                    libxml_clear_errors();
                }
                $this->loadDom($dom);
            }

            public function getErrors()
            {
                return $this->_libxmlErrors;
            }

            public function __invoke($expression)
            {
                return $this->get($expression);
            }

            public function get($expression, $compile = true)
            {
                return $this->getElements($this->getXpathSubquery($expression, false, $compile));
            }

            protected function getNodes()
            {
            }

            public function getDom($asIs = false)
            {
                if ($asIs) {
                    return $this->_dom;
                }
                if ($this->_dom instanceof DOMDocument) {
                    return $this->_dom;
                } elseif ($this->_dom instanceof DOMNodeList || $this->_dom instanceof DOMElement) {
                    if ($this->_tempDom === null) {
                        $this->_tempDom = new DOMDocument('1.0', 'UTF-8');
                        $root = $this->_tempDom->createElement('root');
                        $this->_tempDom->appendChild($root);
                        if ($this->_dom instanceof DOMNodeList) {
                            foreach ($this->_dom as $domElement) {
                                $domNode = $this->_tempDom->importNode($domElement, true);
                                $root->appendChild($domNode);
                            }
                        } else {
                            $domNode = $this->_tempDom->importNode($this->_dom, true);
                            $root->appendChild($domNode);
                        }
                    }
                    return $this->_tempDom;
                }
            }

            protected function getXpath()
            {
                if ($this->_xpath === null) {
                    $this->_xpath = new DOMXpath($this->getDom());
                }
                return $this->_xpath;
            }

            public function getXpathSubquery($expression, $rel = false, $compile = true)
            {
                if ($compile) {
                    $key = $expression . ($rel ? '>' : '*');
                    if (isset(self::$_compiledXpath[$key])) {
                        return self::$_compiledXpath[$key];
                    }
                }
                $query = '';
                if (preg_match(self::regexp, $expression, $subs)) {
                    $brackets = array();
                    if (isset($subs['id']) && '' !== $subs['id']) {
                        $brackets[] = "@id='" . $subs['id'] . "'";
                    }
                    if (isset($subs['attr']) && '' !== $subs['attr']) {
                        if (!(isset($subs['value']))) {
                            $brackets[] = "@" . $subs['attr'];
                        } else {
                            $attrValue = !empty($subs['value']) ? $subs['value'] : '';
                            $brackets[] = "@" . $subs['attr'] . "='" . $attrValue . "'";
                        }
                    }
                    if (isset($subs['class']) && '' !== $subs['class']) {
                        $brackets[] = 'contains(concat(" ", normalize-space(@class), " "), " ' . $subs['class'] . ' ")';
                    }
                    if (isset($subs['pseudo']) && '' !== $subs['pseudo']) {
                        if ('first-child' === $subs['pseudo']) {
                            $brackets[] = '1';
                        } elseif ('last-child' === $subs['pseudo']) {
                            $brackets[] = 'last()';
                        } elseif ('nth-child' === $subs['pseudo']) {
                            if (isset($subs['expr']) && '' !== $subs['expr']) {
                                $e = $subs['expr'];
                                if ('odd' === $e) {
                                    $brackets[] = '(position() -1) mod 2 = 0 and position() >= 1';
                                } elseif ('even' === $e) {
                                    $brackets[] = 'position() mod 2 = 0 and position() >= 0';
                                } elseif (preg_match("/^[0-9]+$/", $e)) {
                                    $brackets[] = 'position() = ' . $e;
                                } elseif (preg_match("/^((?P<mul>[0-9]+)n\+)(?P<pos>[0-9]+)$/is", $e, $esubs)) {
                                    if (isset($esubs['mul'])) {
                                        $brackets[] = '(position() -' . $esubs['pos'] . ') mod ' . $esubs['mul'] . ' = 0 and position() >= ' . $esubs['pos'] . '';
                                    } else {
                                        $brackets[] = '' . $e . '';
                                    }
                                }
                            }
                        }
                    }
                    $query = ($rel ? '/' : '//') .
                        ((isset($subs['tag']) && '' !== $subs['tag']) ? $subs['tag'] : '*') .
                        (($c = count($brackets)) ?
                            ($c > 1 ? '[(' . implode(') and (', $brackets) . ')]' : '[' . implode(' and ', $brackets) . ']')
                            : '');
                    $left = trim(substr($expression, strlen($subs[0])));
                    if ('' !== $left) {
                        $query .= $this->getXpathSubquery($left, isset($subs['rel']) ? '>' === $subs['rel'] : false, $compile);
                    }
                }
                if ($compile) {
                    self::$_compiledXpath[$key] = $query;
                }
                return $query;
            }

            protected function getElements($xpathQuery)
            {
                if (strlen($xpathQuery)) {
                    $nodeList = $this->getXpath()->query($xpathQuery);
                    if ($nodeList === false) {
                        throw new Exception('Malformed xpath');
                    }
                    return self::fromDom($nodeList);
                }
            }

            public function toDom($asIs = false)
            {
                return $this->getDom($asIs);
            }

            public function toXml()
            {
                return $this->getDom()->saveXML();
            }

            public function toArray($xnode = null)
            {
                $array = array();
                if ($xnode === null) {
                    if ($this->_dom instanceof DOMNodeList) {
                        foreach ($this->_dom as $node) {
                            $array[] = $this->toArray($node);
                        }
                        return $array;
                    }
                    $node = $this->getDom();
                } else {
                    $node = $xnode;
                }
                if (in_array($node->nodeType, array(XML_TEXT_NODE, XML_COMMENT_NODE))) {
                    return $node->nodeValue;
                }
                if ($node->hasAttributes()) {
                    foreach ($node->attributes as $attr) {
                        $array[$attr->nodeName] = $attr->nodeValue;
                    }
                }
                if ($node->hasChildNodes()) {
                    foreach ($node->childNodes as $childNode) {
                        $array[$childNode->nodeName][] = $this->toArray($childNode);
                    }
                }
                if ($xnode === null) {
                    $a = reset($array);
                    return reset($a); // first child
                }
                return $array;
            }

            public function getIterator()
            {
                $a = $this->toArray();
                return new ArrayIterator($a);
            }

            protected function _toTextArray($node = null, $skipChildren = false, $singleLevel = true)
            {
                $array = array();
                if ($node === null) {
                    $node = $this->getDom();
                }
                if ($node instanceof DOMNodeList) {
                    foreach ($node as $child) {
                        if ($singleLevel) {
                            $array = array_merge($array, $this->_toTextArray($child, $skipChildren, $singleLevel));
                        } else {
                            $array[] = $this->_toTextArray($child, $skipChildren, $singleLevel);
                        }
                    }
                    return $array;
                }
                if (XML_TEXT_NODE === $node->nodeType) {
                    return array($node->nodeValue);
                }
                if (!$skipChildren) {
                    if ($node->hasChildNodes()) {
                        foreach ($node->childNodes as $childNode) {
                            if ($singleLevel) {
                                $array = array_merge($array, $this->_toTextArray($childNode, $skipChildren, $singleLevel));
                            } else {
                                $array[] = $this->_toTextArray($childNode, $skipChildren, $singleLevel);
                            }
                        }
                    }
                }
                return $array;
            }

            public function toTextArray($skipChildren = false, $singleLevel = true)
            {
                return $this->_toTextArray($this->_dom, $skipChildren, $singleLevel);
            }

            public function toText($glue = ' ', $skipChildren = false)
            {
                return implode($glue, $this->toTextArray($skipChildren, true));
            }
        }

        $data2 = [
            ['ЖСК "Аврора"', 'http://kskforum.kz/forum/31-zhsk-avrora/', 'http://kskforum.kz/forum/1216-otchety-plany-rabot/'],
            ['ПЖК "Азамат"', 'http://kskforum.kz/forum/35-pzhk-azamat/', 'http://kskforum.kz/forum/1063-otchety-plany-rabot/'],
            ['ПКСК "А-3/5"', 'http://kskforum.kz/forum/30-pksk-a-35/', 'http://kskforum.kz/forum/1249-otchety-plany-rabot/'],
            ['ПКСК "Адал"', 'http://kskforum.kz/forum/33-pksk-adal/', 'http://kskforum.kz/forum/1260-otchety-plany-rabot/'],
            ['ЖК "Алма"', 'http://kskforum.kz/forum/51-zhk-alma/', 'http://kskforum.kz/forum/1291-otchety-plany-rabot/'],
            ['ПКСК "Алмаз"', 'http://kskforum.kz/forum/53-pksk-almaz/', 'http://kskforum.kz/forum/1357-otchety-plany-rabot/'],
            ['ПКСК "Айман"', 'http://kskforum.kz/forum/38-pksk-ajman/', 'http://kskforum.kz/forum/1371-otchety-plany-rabot/'],
            ['КСК "Академия"', 'http://kskforum.kz/forum/40-ksk-akademiia/', 'http://kskforum.kz/forum/1415-otchety-plany-rabot/'],
            ['ПКСК "Аксай-3 а"', 'http://kskforum.kz/forum/41-pksk-aksaj-3-a/', 'http://kskforum.kz/forum/1426-otchety-plany-rabot/'],
            ['ПКСК "Алмас"', 'http://kskforum.kz/forum/55-pksk-almas/', 'http://kskforum.kz/forum/1404-otchety-plany-rabot/'],
            ['ПКСК "Алуа"', 'http://kskforum.kz/forum/56-pksk-alua/', 'http://kskforum.kz/forum/1460-otchety-plany-rabot/'],
            ['ПК "Аксай-Монолит"', 'http://kskforum.kz/forum/43-pk-aksaj-monolit/', 'http://kskforum.kz/forum/1482-otchety-plany-rabot/'],
            ['ПКСК "Аксай-4"', 'http://kskforum.kz/forum/44-pksk-aksaj-4/', 'http://kskforum.kz/forum/2940-otchety-plany-rabot/'],
            ['ПКСК "Алия"', 'http://kskforum.kz/forum/49-pksk-aliia/', 'http://kskforum.kz/forum/3033-otchety-plany-rabot/'],
            ['ПКСК "Арна"', 'http://kskforum.kz/forum/65-pksk-arna/', 'http://kskforum.kz/forum/2908-otchety-plany-rabot/'],
            ['ПКСК "Асель"', 'http://kskforum.kz/forum/66-pksk-asel/', 'http://kskforum.kz/forum/3146-otchety-plany-rabot/'],
            ['ЖК "Астра"', 'http://kskforum.kz/forum/69-zhk-astra/', 'http://kskforum.kz/forum/2963-otchety-plany-rabot/'],
            ['ПК "АК Отау"', 'http://kskforum.kz/forum/993-pk-ak-otau/', 'http://kskforum.kz/forum/1269-otchety-plany-rabot/'],
            ['ПК "Arау Hofnung"', 'http://kskforum.kz/forum/1020-pk-arau-hofnung/', 'http://kskforum.kz/forum/1544-otchety-plany-rabot/'],
            ['ТОО "Алматы Тұрғын Үй"', 'http://kskforum.kz/forum/2783-too-almaty-t%D2%B1r%D2%93yn-%D2%AFj/', 'http://kskforum.kz/forum/2785-otchety-plany-rabot/'],
            ['ПК "АСУ"', 'http://kskforum.kz/forum/2907-pk-asu/', 'http://kskforum.kz/forum/2909-otchety-plany-rabot/'],
            ['ПКСК "Автомобилист"', 'http://kskforum.kz/forum/3027-pksk-avtomobilist/', 'http://kskforum.kz/forum/3028-otchety-plany-rabot/'],
            ['ПКСК "Байторы"', 'http://kskforum.kz/forum/73-pksk-bajtory/', 'http://kskforum.kz/forum/317-otchety-plany-rabot/'],
            ['ПКСК "Батыр"', 'http://kskforum.kz/forum/74-pksk-batyr/', 'http://kskforum.kz/forum/2956-otchety-plany-rabot/'],
            ['ПКСК "Бектур"', 'http://kskforum.kz/forum/76-pksk-bektur/', 'http://kskforum.kz/forum/2910-otchety-plany-rabot/'],
            ['КСК "Берёзка"', 'http://kskforum.kz/forum/79-ksk-beryozka/', 'http://kskforum.kz/forum/2939-otchety-plany-rabot/'],
            ['ПКСК "Беркут"', 'http://kskforum.kz/forum/80-pksk-berkut/', 'http://kskforum.kz/forum/2964-otchety-plany-rabot/'],
            ['ПКСК "Болашак"', 'http://kskforum.kz/forum/88-pksk-bolashak/', 'http://kskforum.kz/forum/3166-otchety-plany-rabot/'],
            ['ПКСК "Бояулы"', 'http://kskforum.kz/forum/90-pksk-boiauly/', 'http://kskforum.kz/forum/2974-otchety-plany-rabot/'],
            ['ПКСК "Бiрлiк"', 'http://kskforum.kz/forum/1023-pksk-birlik/', 'http://kskforum.kz/forum/1616-otchety-plany-rabot/'],
            ['ПК "БН Жастар"', 'http://kskforum.kz/forum/3036-pk-bn-zhastar/', 'http://kskforum.kz/forum/3037-otchety-plany-rabot/'],
            ['ПКСК Верный', 'http://kskforum.kz/forum/92-pksk-vernyj/', 'http://kskforum.kz/forum/1110-otchety-plany-rabot/'],
            ['ПКСК "Верный-2"', 'http://kskforum.kz/forum/93-pksk-vernyj-2/', 'http://kskforum.kz/forum/2635-otchety-plany-rabot/'],
            ['ПКСК "Восход"', 'http://kskforum.kz/forum/97-pksk-voskhod/', 'http://kskforum.kz/forum/3062-otchety-plany-rabot/'],
            ['ПК "Виктория – Жетысу 21"', 'http://kskforum.kz/forum/1024-pk-viktoriia-%E2%80%93-zhetysu-21/', 'http://kskforum.kz/forum/1638-otchety-plany-rabot/'],
            ['ЖК "Галактика"', 'http://kskforum.kz/forum/99-zhk-galaktika/', 'http://kskforum.kz/forum/2748-otchety-plany-rabot/'],
            ['ПКСК "Домостроитель-3"', 'http://kskforum.kz/forum/110-pksk-domostroitel-3/', 'http://kskforum.kz/forum/3063-otchety-plany-rabot/'],
            ['ПКСК "Достык"', 'http://kskforum.kz/forum/112-pksk-dostyk/', 'http://kskforum.kz/forum/2951-otchety-plany-rabot/'],
            ['ЖК "Достык"', 'http://kskforum.kz/forum/113-zhk-dostyk/', 'http://kskforum.kz/forum/2955-otchety-plany-rabot/'],
            ['ПКСК "Дубок"', 'http://kskforum.kz/forum/114-pksk-dubok/', 'http://kskforum.kz/forum/3043-otchety-plany-rabot/'],
            ['ЖК "Дастан"', 'http://kskforum.kz/forum/102-zhk-dastan/', 'http://kskforum.kz/forum/2897-otchety-plany-rabot/'],
            ['ПКСК "Даулет"', 'http://kskforum.kz/forum/105-pksk-daulet/', 'http://kskforum.kz/forum/2893-otchety-plany-rabot/'],
            ['ПК "Дом - Адылет"', 'http://kskforum.kz/forum/2915-pk-dom-adylet/', 'http://kskforum.kz/forum/2916-otchety-plany-rabot/'],
            ['ПК "Достык Жетысу"', 'http://kskforum.kz/forum/3012-pk-dostyk-zhetysu/', 'http://kskforum.kz/forum/3013-otchety-plany-rabot/'],
            ['ЖК "Домостроитель-2"', 'http://kskforum.kz/forum/3025-zhk-domostroitel-2/', 'http://kskforum.kz/forum/3026-otchety-plany-rabot/'],
            ['ПК "Жанна АБК"', 'http://kskforum.kz/forum/117-pk-zhanna-abk/', 'http://kskforum.kz/forum/3231-otchety-plany-rabot/'],
            ['КСК "Жана-Нур"', 'http://kskforum.kz/forum/118-ksk-zhana-nur/', 'http://kskforum.kz/forum/3064-otchety-plany-rabot/'],
            ['ЖК "Жемчуг"', 'http://kskforum.kz/forum/120-zhk-zhemchug/', 'http://kskforum.kz/forum/3060-otchety-plany-rabot/'],
            ['ПКСК "Жетысу"', 'http://kskforum.kz/forum/121-pksk-zhetysu/', 'http://kskforum.kz/forum/2913-otchety-plany-rabot/'],
            ['ПК "Жетысу kulta 2"', 'http://kskforum.kz/forum/122-pk-zhetysu-kulta-2/', 'http://kskforum.kz/forum/1671-otchety-plany-rabot/'],
            ['ПКСК "Жетысу-2"', 'http://kskforum.kz/forum/123-pksk-zhetysu-2/', 'http://kskforum.kz/forum/2904-otchety-plany-rabot/'],
            ['ПКСК "Жетысу-24"', 'http://kskforum.kz/forum/124-pksk-zhetysu-24/', 'http://kskforum.kz/forum/3233-otchety-plany-rabot/'],
            ['ПКСК "Жетысу-Нуры"', 'http://kskforum.kz/forum/2218-pksk-zhetysu-nury/', 'http://kskforum.kz/forum/2226-otchety-plany-rabot/'],
            ['ПКСК "Жулдыз-16"', 'http://kskforum.kz/forum/125-pksk-zhuldyz-16/', 'http://kskforum.kz/forum/3023-otchety-plany-rabot/'],
            ['ПКСК "Жулдыз-19"', 'http://kskforum.kz/forum/126-pksk-zhuldyz-19/', 'http://kskforum.kz/forum/2898-otchety-plany-rabot/'],
            ['ПКСК "Журавушка-Тырна"', 'http://kskforum.kz/forum/127-pksk-zhuravushka-tyrna/', 'http://kskforum.kz/forum/2899-otchety-i-plany-rabot/'],
            ['ПК "Жолдастар"', 'http://kskforum.kz/forum/2969-pk-zholdastar/', 'http://kskforum.kz/forum/2970-otchety-plany-rabot/'],
            ['КСК "Жарык"', 'http://kskforum.kz/forum/2992-ksk-zharyk/', 'http://kskforum.kz/forum/2993-otchety-plany-rabot/'],
            ['ПКСК "Западный"', 'http://kskforum.kz/forum/129-pksk-zapadnyj/', 'http://kskforum.kz/forum/3050-otchety-plany-rabot/'],
            ['ПК "Заря"', 'http://kskforum.kz/forum/130-pk-zaria/', 'http://kskforum.kz/forum/2973-otchety-plany-rabot/'],
            ['КСП "Ісмер"', 'http://kskforum.kz/forum/133-ksp-%D1%96smer/', 'http://kskforum.kz/forum/2903-otchety-plany-rabot/'],
            ['ПКСК "КААС"', 'http://kskforum.kz/forum/134-pksk-kaas/', 'http://kskforum.kz/forum/1715-otchety-plany-rabot/'],
            ['КСК "Кайрат"', 'http://kskforum.kz/forum/135-ksk-kajrat/', 'http://kskforum.kz/forum/2957-otchety-plany-rabot/'],
            ['ПКСК "Каратал"', 'http://kskforum.kz/forum/138-pksk-karatal/', 'http://kskforum.kz/forum/2905-otchety-plany-rabot/'],
            ['ПКСК "Космос"', 'http://kskforum.kz/forum/143-pksk-kosmos/', 'http://kskforum.kz/forum/2960-otchety-plany-rabot/'],
            ['ПК "Куаныш мекені"', 'http://kskforum.kz/forum/146-pk-kuanysh-meken%D1%96/', 'http://kskforum.kz/forum/3038-otchety-plany-rabot/'],
            ['КСК "Лидер-1"', 'http://kskforum.kz/forum/148-ksk-lider-1/', 'http://kskforum.kz/forum/3034-otchety-plany-rabot/'],
            ['ПКСК "Луч"', 'http://kskforum.kz/forum/151-pksk-luch/', 'http://kskforum.kz/forum/1858-otchety-plany-rabot/'],
            ['ПКСК "Максат"', 'http://kskforum.kz/forum/152-pksk-maksat/', 'http://kskforum.kz/forum/1244-otchety-plany-rabot/'],
            ['ЖК "Максат"', 'http://kskforum.kz/forum/153-zhk-maksat/', 'http://kskforum.kz/forum/2999-otchety-plany-rabot/'],
            ['ПКСК "Мамыр"', 'http://kskforum.kz/forum/155-pksk-mamyr/', 'http://kskforum.kz/forum/2912-otchety-plany-rabot/'],
            ['ПКСК "Мамыр-7"', 'http://kskforum.kz/forum/156-pksk-mamyr-7/', 'http://kskforum.kz/forum/2882-otchety-plany-rabot/'],
            ['ПКСК "Марс"', 'http://kskforum.kz/forum/160-pksk-mars/', 'http://kskforum.kz/forum/3065-otchety-plany-rabot/'],
            ['ПК "Мекен – Жай"', 'http://kskforum.kz/forum/166-pk-meken-%E2%80%93-zhaj/', 'http://kskforum.kz/forum/2806-otchety-plany-rabot/'],
            ['ПКСК "Мейрам"', 'http://kskforum.kz/forum/167-pksk-mejram/', 'http://kskforum.kz/forum/1880-otchety-plany-rabot/'],
            ['ЖК "Меруерт"', 'http://kskforum.kz/forum/170-zhk-meruert/', 'http://kskforum.kz/forum/2896-otchety-plany-rabot/'],
            ['ЖК "Мир"', 'http://kskforum.kz/forum/173-zhk-mir/', 'http://kskforum.kz/forum/3029-otchety-plany-rabot/'],
            ['КСК Надежда', 'http://kskforum.kz/forum/178-ksk-nadezhda/', 'http://kskforum.kz/forum/1052-otchety-plany-rabot/'],
            ['ПКСК "Надежда"', 'http://kskforum.kz/forum/179-pksk-nadezhda/', 'http://kskforum.kz/forum/2891-otchety-plany-rabot/'],
            ['ПКСК "Наурыз"', 'http://kskforum.kz/forum/180-pksk-nauryz/', 'http://kskforum.kz/forum/3270-otchety-plany-rabot/'],
            ['ПКСК "Нейрон"', 'http://kskforum.kz/forum/2966-pksk-nejron/', 'http://kskforum.kz/forum/2967-otchety-plany-rabot/'],
            ['ПК "Орын уй"', 'http://kskforum.kz/forum/186-pk-oryn-uj/', 'http://kskforum.kz/forum/2958-otchety-plany-rabot/'],
            ['ПКСК "Отау"', 'http://kskforum.kz/forum/188-pksk-otau/', 'http://kskforum.kz/forum/2901-otchety-plany-rabot/'],
            ['ПК "Отау-8 Virta"', 'http://kskforum.kz/forum/189-pk-otau-8-virta/', 'http://kskforum.kz/forum/1916-otchety-plany-rabot/'],
            ['ПК "Oberon"', 'http://kskforum.kz/forum/2990-pk-oberon/', 'http://kskforum.kz/forum/2991-otchety-plany-rabot/'],
            ['ПК Премьера 29', 'http://kskforum.kz/forum/11-pk-premera-29/', 'http://kskforum.kz/forum/2639-otchety-i-plany-rabot/'],
            ['ПКСК "Позитив"', 'http://kskforum.kz/forum/194-pksk-pozitiv/', 'http://kskforum.kz/forum/3283-otchety-plany-rabot/'],
            ['ЖК "Правда"', 'http://kskforum.kz/forum/195-zhk-pravda/', 'http://kskforum.kz/forum/2920-otchety-plany-rabot/'],
            ['ПК "Престиж 7"', 'http://kskforum.kz/forum/196-pk-prestizh-7/', 'http://kskforum.kz/forum/3015-otchety-plany-rabot/'],
            ['ПКСК "Прогресс"', 'http://kskforum.kz/forum/197-pksk-progress/', 'http://kskforum.kz/forum/3030-otchety-plany-rabot/'],
            ['ЖК "Прометей"', 'http://kskforum.kz/forum/198-zhk-prometej/', 'http://kskforum.kz/forum/2954-otchety-plany-rabot/'],
            ['ПКСК "Радуга"', 'http://kskforum.kz/forum/199-pksk-raduga/', 'http://kskforum.kz/forum/2889-otchety-plany-rabot/'],
            ['ПКСК "Сайран"', 'http://kskforum.kz/forum/206-pksk-sajran/', 'http://kskforum.kz/forum/2952-otchety-plany-rabot/'],
            ['ПКСК "Сайран-2"', 'http://kskforum.kz/forum/207-pksk-sajran-2/', 'http://kskforum.kz/forum/2424-otchety-plany-rabot/'],
            ['ПКСК "Север"', 'http://kskforum.kz/forum/212-pksk-sever/', 'http://kskforum.kz/forum/3003-otchety-plany-rabot/'],
            ['ПКСК Серпин', 'http://kskforum.kz/forum/213-pksk-serpin/', 'http://kskforum.kz/forum/1075-otchety-plany-rabot/'],
            ['КСК "Солнышко"', 'http://kskforum.kz/forum/215-ksk-solnyshko/', 'http://kskforum.kz/forum/3066-otchety-plany-rabot/'],
            ['ПКСК "Согласие"', 'http://kskforum.kz/forum/216-pksk-soglasie/', 'http://kskforum.kz/forum/2914-otchety-plany-rabot/'],
            ['ЖК "Спутник"', 'http://kskforum.kz/forum/220-zhk-sputnik/', 'http://kskforum.kz/forum/2968-otchety-plany-rabot/'],
            ['ПКСК "Спутник"', 'http://kskforum.kz/forum/221-pksk-sputnik/', 'http://kskforum.kz/forum/2906-otchety-plany-rabot/'],
            ['ЖК "Сункар"', 'http://kskforum.kz/forum/223-zhk-sunkar/', 'http://kskforum.kz/forum/2887-otchety-plany-rabot/'],
            ['ПКСК "Тастак-1-Пуск"', 'http://kskforum.kz/forum/226-pksk-tastak-1-pusk/', 'http://kskforum.kz/forum/2902-otchety-plany-rabot/'],
            ['ПК Тау Самал Навои', 'http://kskforum.kz/forum/2020-pk-tau-samal-navoi/', 'http://kskforum.kz/forum/2028-otchety-plany-rabot/'],
            ['ПКСК "Таугуль"', 'http://kskforum.kz/forum/227-pksk-taugul/', 'http://kskforum.kz/forum/2959-otchety-plany-rabot/'],
            ['ЖК "Тянь-Шань"', 'http://kskforum.kz/forum/231-zhk-tian-shan/', 'http://kskforum.kz/forum/3222-otchety-plany-rabot/'],
            ['ПКСК "Тюльпан"', 'http://kskforum.kz/forum/232-pksk-tiulpan/', 'http://kskforum.kz/forum/3067-otchety-plany-rabot/'],
            ['ПКСК "Улан"', 'http://kskforum.kz/forum/233-pksk-ulan/', 'http://kskforum.kz/forum/2917-otchety-plany-rabot/'],
            ['ПКСК "Управление домами №3"', 'http://kskforum.kz/forum/234-pksk-upravlenie-domami-%E2%84%963/', 'http://kskforum.kz/forum/3035-otchety-plany-rabot/'],
            ['ПКСК "Управление домами-14"', 'http://kskforum.kz/forum/235-pksk-upravlenie-domami-14/', 'http://kskforum.kz/forum/2953-otchety-plany-rabot/'],
            ['ПКСК "Уют-28"', 'http://kskforum.kz/forum/236-pksk-uiut-28/', 'http://kskforum.kz/forum/2911-otchety-plany-rabot/'],
            ['ПКСК Школьник', 'http://kskforum.kz/forum/239-pksk-shkolnik/', 'http://kskforum.kz/forum/2130-otchety-plany-rabot/']
        ];
        $data = [
            ['ЖСК "Аврора"', 'http://kskforum.kz/forum/31-zhsk-avrora/'],
            ['ПЖК "Азамат"', 'http://kskforum.kz/forum/35-pzhk-azamat/'],
            ['ПКСК "А-3/5"', 'http://kskforum.kz/forum/30-pksk-a-35/'],
            ['ПКСК "Адал"', 'http://kskforum.kz/forum/33-pksk-adal/'],
            ['ЖК "Алма"', 'http://kskforum.kz/forum/51-zhk-alma/'],
            ['ПКСК "Алмаз"', 'http://kskforum.kz/forum/53-pksk-almaz/'],
            ['ПКСК "Айман"', 'http://kskforum.kz/forum/38-pksk-ajman/'],
            ['КСК "Академия"', 'http://kskforum.kz/forum/40-ksk-akademiia/'],
            ['ПКСК "Аксай-3 а"', 'http://kskforum.kz/forum/41-pksk-aksaj-3-a/'],
            ['ПКСК "Алмас"', 'http://kskforum.kz/forum/55-pksk-almas/'],
            ['ПКСК "Алуа"', 'http://kskforum.kz/forum/56-pksk-alua/'],
            ['ПК "Аксай-Монолит"', 'http://kskforum.kz/forum/43-pk-aksaj-monolit/'],
            ['ПКСК "Аксай-4"', 'http://kskforum.kz/forum/44-pksk-aksaj-4/'],
            ['ПКСК "Алия"', 'http://kskforum.kz/forum/49-pksk-aliia/'],
            ['ПКСК "Арна"', 'http://kskforum.kz/forum/65-pksk-arna/'],
            ['ПКСК "Асель"', 'http://kskforum.kz/forum/66-pksk-asel/'],
            ['ЖК "Астра"', 'http://kskforum.kz/forum/69-zhk-astra/'],
            ['ПК "АК Отау"', 'http://kskforum.kz/forum/993-pk-ak-otau/'],
            ['ПК "Arау Hofnung"', 'http://kskforum.kz/forum/1020-pk-arau-hofnung/'],
            ['ТОО "Алматы Тұрғын Үй"', 'http://kskforum.kz/forum/2783-too-almaty-t%D2%B1r%D2%93yn-%D2%AFj/'],
            ['ПК "АСУ"', 'http://kskforum.kz/forum/2907-pk-asu/'],
            ['ПКСК "Автомобилист"', 'http://kskforum.kz/forum/3027-pksk-avtomobilist/'],
            ['ПКСК "Байторы"', 'http://kskforum.kz/forum/73-pksk-bajtory/'],
            ['ПКСК "Батыр"', 'http://kskforum.kz/forum/74-pksk-batyr/'],
            ['ПКСК "Бектур"', 'http://kskforum.kz/forum/76-pksk-bektur/'],
            ['КСК "Берёзка"', 'http://kskforum.kz/forum/79-ksk-beryozka/'],
            ['ПКСК "Беркут"', 'http://kskforum.kz/forum/80-pksk-berkut/'],
            ['ПКСК "Болашак"', 'http://kskforum.kz/forum/88-pksk-bolashak/'],
            ['ПКСК "Бояулы"', 'http://kskforum.kz/forum/90-pksk-boiauly/'],
            ['ПКСК "Бiрлiк"', 'http://kskforum.kz/forum/1023-pksk-birlik/'],
            ['ПК "БН Жастар"', 'http://kskforum.kz/forum/3036-pk-bn-zhastar/'],
            ['ПКСК Верный', 'http://kskforum.kz/forum/92-pksk-vernyj/'],
            ['ПКСК "Верный-2"', 'http://kskforum.kz/forum/93-pksk-vernyj-2/'],
            ['ПКСК "Восход"', 'http://kskforum.kz/forum/97-pksk-voskhod/'],
            ['ПК "Виктория – Жетысу 21"', 'http://kskforum.kz/forum/1024-pk-viktoriia-%E2%80%93-zhetysu-21/'],
            ['ЖК "Галактика"', 'http://kskforum.kz/forum/99-zhk-galaktika/'],
            ['ПКСК "Домостроитель-3"', 'http://kskforum.kz/forum/110-pksk-domostroitel-3/'],
            ['ПКСК "Достык"', 'http://kskforum.kz/forum/112-pksk-dostyk/'],
            ['ЖК "Достык"', 'http://kskforum.kz/forum/113-zhk-dostyk/'],
            ['ПКСК "Дубок"', 'http://kskforum.kz/forum/114-pksk-dubok/'],
            ['ЖК "Дастан"', 'http://kskforum.kz/forum/102-zhk-dastan/'],
            ['ПКСК "Даулет"', 'http://kskforum.kz/forum/105-pksk-daulet/'],
            ['ПК "Дом - Адылет"', 'http://kskforum.kz/forum/2915-pk-dom-adylet/'],
            ['ПК "Достык Жетысу"', 'http://kskforum.kz/forum/3012-pk-dostyk-zhetysu/'],
            ['ЖК "Домостроитель-2"', 'http://kskforum.kz/forum/3025-zhk-domostroitel-2/'],
            ['ПК "Жанна АБК"', 'http://kskforum.kz/forum/117-pk-zhanna-abk/'],
            ['КСК "Жана-Нур"', 'http://kskforum.kz/forum/118-ksk-zhana-nur/'],
            ['ЖК "Жемчуг"', 'http://kskforum.kz/forum/120-zhk-zhemchug/'],
            ['ПКСК "Жетысу"', 'http://kskforum.kz/forum/121-pksk-zhetysu/'],
            ['ПК "Жетысу kulta 2"', 'http://kskforum.kz/forum/122-pk-zhetysu-kulta-2/'],
            ['ПКСК "Жетысу-2"', 'http://kskforum.kz/forum/123-pksk-zhetysu-2/'],
            ['ПКСК "Жетысу-24"', 'http://kskforum.kz/forum/124-pksk-zhetysu-24/'],
            ['ПКСК "Жетысу-Нуры"', 'http://kskforum.kz/forum/2218-pksk-zhetysu-nury/'],
            ['ПКСК "Жулдыз-16"', 'http://kskforum.kz/forum/125-pksk-zhuldyz-16/'],
            ['ПКСК "Жулдыз-19"', 'http://kskforum.kz/forum/126-pksk-zhuldyz-19/'],
            ['ПКСК "Журавушка-Тырна"', 'http://kskforum.kz/forum/127-pksk-zhuravushka-tyrna/'],
            ['ПК "Жолдастар"', 'http://kskforum.kz/forum/2969-pk-zholdastar/'],
            ['КСК "Жарык"', 'http://kskforum.kz/forum/2992-ksk-zharyk/'],
            ['ПКСК "Западный"', 'http://kskforum.kz/forum/129-pksk-zapadnyj/'],
            ['ПК "Заря"', 'http://kskforum.kz/forum/130-pk-zaria/'],
            ['КСП "Ісмер"', 'http://kskforum.kz/forum/133-ksp-%D1%96smer/'],
            ['ПКСК "КААС"', 'http://kskforum.kz/forum/134-pksk-kaas/'],
            ['КСК "Кайрат"', 'http://kskforum.kz/forum/135-ksk-kajrat/'],
            ['ПКСК "Каратал"', 'http://kskforum.kz/forum/138-pksk-karatal/'],
            ['ПКСК "Космос"', 'http://kskforum.kz/forum/143-pksk-kosmos/'],
            ['ПК "Куаныш мекені"', 'http://kskforum.kz/forum/146-pk-kuanysh-meken%D1%96/'],
            ['КСК "Лидер-1"', 'http://kskforum.kz/forum/148-ksk-lider-1/'],
            ['ПКСК "Луч"', 'http://kskforum.kz/forum/151-pksk-luch/'],
            ['ПКСК "Максат"', 'http://kskforum.kz/forum/152-pksk-maksat/'],
            ['ЖК "Максат"', 'http://kskforum.kz/forum/153-zhk-maksat/'],
            ['ПКСК "Мамыр"', 'http://kskforum.kz/forum/155-pksk-mamyr/'],
            ['ПКСК "Мамыр-7"', 'http://kskforum.kz/forum/156-pksk-mamyr-7/'],
            ['ПКСК "Марс"', 'http://kskforum.kz/forum/160-pksk-mars/'],
            ['ПК "Мекен – Жай"', 'http://kskforum.kz/forum/166-pk-meken-%E2%80%93-zhaj/'],
            ['ПКСК "Мейрам"', 'http://kskforum.kz/forum/167-pksk-mejram/'],
            ['ЖК "Меруерт"', 'http://kskforum.kz/forum/170-zhk-meruert/'],
            ['ЖК "Мир"', 'http://kskforum.kz/forum/173-zhk-mir/'],
            ['КСК Надежда', 'http://kskforum.kz/forum/178-ksk-nadezhda/'],
            ['ПКСК "Надежда"', 'http://kskforum.kz/forum/179-pksk-nadezhda/'],
            ['ПКСК "Наурыз"', 'http://kskforum.kz/forum/180-pksk-nauryz/'],
            ['ПКСК "Нейрон"', 'http://kskforum.kz/forum/2966-pksk-nejron/'],
            ['ПК "Орын уй"', 'http://kskforum.kz/forum/186-pk-oryn-uj/'],
            ['ПКСК "Отау"', 'http://kskforum.kz/forum/188-pksk-otau/'],
            ['ПК "Отау-8 Virta"', 'http://kskforum.kz/forum/189-pk-otau-8-virta/'],
            ['ПК "Oberon"', 'http://kskforum.kz/forum/2990-pk-oberon/'],
            ['ПК Премьера 29', 'http://kskforum.kz/forum/11-pk-premera-29/'],
            ['ПКСК "Позитив"', 'http://kskforum.kz/forum/194-pksk-pozitiv/'],
            ['ЖК "Правда"', 'http://kskforum.kz/forum/195-zhk-pravda/'],
            ['ПК "Престиж 7"', 'http://kskforum.kz/forum/196-pk-prestizh-7/'],
            ['ПКСК "Прогресс"', 'http://kskforum.kz/forum/197-pksk-progress/'],
            ['ЖК "Прометей"', 'http://kskforum.kz/forum/198-zhk-prometej/'],
            ['ПКСК "Радуга"', 'http://kskforum.kz/forum/199-pksk-raduga/'],
            ['ПКСК "Сайран"', 'http://kskforum.kz/forum/206-pksk-sajran/'],
            ['ПКСК "Сайран-2"', 'http://kskforum.kz/forum/207-pksk-sajran-2/'],
            ['ПКСК "Север"', 'http://kskforum.kz/forum/212-pksk-sever/'],
            ['ПКСК Серпин', 'http://kskforum.kz/forum/213-pksk-serpin/'],
            ['КСК "Солнышко"', 'http://kskforum.kz/forum/215-ksk-solnyshko/'],
            ['ПКСК "Согласие"', 'http://kskforum.kz/forum/216-pksk-soglasie/'],
            ['ЖК "Спутник"', 'http://kskforum.kz/forum/220-zhk-sputnik/'],
            ['ПКСК "Спутник"', 'http://kskforum.kz/forum/221-pksk-sputnik/'],
            ['ЖК "Сункар"', 'http://kskforum.kz/forum/223-zhk-sunkar/'],
            ['ПКСК "Тастак-1-Пуск"', 'http://kskforum.kz/forum/226-pksk-tastak-1-pusk/'],
            ['ПК Тау Самал Навои', 'http://kskforum.kz/forum/2020-pk-tau-samal-navoi/'],
            ['ПКСК "Таугуль"', 'http://kskforum.kz/forum/227-pksk-taugul/'],
            ['ЖК "Тянь-Шань"', 'http://kskforum.kz/forum/231-zhk-tian-shan/'],
            ['ПКСК "Тюльпан"', 'http://kskforum.kz/forum/232-pksk-tiulpan/'],
            ['ПКСК "Улан"', 'http://kskforum.kz/forum/233-pksk-ulan/'],
            ['ПКСК "Управление домами №3"', 'http://kskforum.kz/forum/234-pksk-upravlenie-domami-%E2%84%963/'],
            ['ПКСК "Управление домами-14"', 'http://kskforum.kz/forum/235-pksk-upravlenie-domami-14/'],
            ['ПКСК "Уют-28"', 'http://kskforum.kz/forum/236-pksk-uiut-28/'],
            ['ПКСК Школьник', 'http://kskforum.kz/forum/239-pksk-shkolnik/']
        ];

//        $i = 0;
//        foreach ($data as $item) {
////            if ($i == 2) {
////            echo $item[0].'-'.;
//            $saw = new nokogiri(file_get_contents($item[1]));
//            $ok = false;
//            foreach ($saw->get('.forum_name') as $forum_name) {
//                $ok = true;
//                $a = $forum_name['strong'][0]['a'][0];
////                \yii\helpers\VarDumper::dump($forum_name, 10, true);
////                echo $a['title'].' - Отчеты';
////                echo "<hr>";
//                if (strpos($a['title'], 'Отчеты') !== false || strpos($a['title'], 'отчеты') !== false) {
//                    echo '[\'' . $item[0] . '\', \'' . $item[1] . '\', \'' . $a['href'] . '\'],';
//                    break;
//                }
//            }
//            if (!$ok) {
//                foreach ($saw->get('.col_c_forum') as $forum_name) {
//                    $ok = true;
//                    $a = $forum_name['h4'][0]['a'][0];
////                        \yii\helpers\VarDumper::dump($forum_name, 10, true);
////                        echo $a['title'].' - Отчеты';
////                        echo "<hr>";
//                    if (strpos($a['title'], 'Отчеты') !== false || strpos($a['title'], 'отчеты') !== false) {
//                        echo '[\'' . $item[0] . '\', \'' . $item[1] . '\', \'' . $a['href'] . '\'],';
//                        break;
//                    }
//                }
//            }
////            }
////            echo "<hr>";
//            $i++;
////            if ($i > 3) break;
//        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                $i = 0;
//        $exist = [];
//        $temp = [];
////                foreach ($data2 as $item) {
//        //            if ($i == 2) {
//        //            echo $item[0].'-'.;
//                    $saw = new nokogiri(file_get_contents('http://kskforum.kz/forum/1249-otchety-plany-rabot/'));
//                    foreach ($saw->get('.col_f_content') as $forum_name) {
//                        $ok = true;
//                        $h4s = $forum_name['h4'];
//                        if (isset($forum_name['span'][0]['span'])) {
//                            $span = $forum_name['span'][0]['span'][0]['#text'][0];
//                        } else {
//                            $span = $forum_name['span'][1]['span'][0]['#text'][0];
//                        }
////                        \yii\helpers\VarDumper::dump($forum_name, 10, true);
////                        echo $a['title'];
//                        echo "<hr>";
//                        foreach ($h4s as $h4) {
//                            $a = $h4['a'][0];
//                            $exist[] = $h4;
//                            $temp[] = [
//                                'name' => $a['span'][0]['#text'][0],
//                                'url' => $a['href'],
//                                'posted_at_str' => $span,
//                                'posted_at' => date('Y-m-d H:i:s', strtotime($span))
//                            ];
////                            if (strpos($a['title'], '4 квартал') != false) {
////                                break;
////                                echo '[\'' . $item[0] . '\', \'' . $a['title'] . '\'],';
////                            }
////                            echo '[\'' . $item[0] . '\', \'' . $a['title'] . '\', \'' . $a['href'] . '\'],';
//                        }
//                    }
////                    break;
//        //            }
//        //            echo "<hr>";
////                    $i++;
////                    if ($i > 3) break;
////                }
//            echo sizeof($exist);
//            echo '<hr>';
////        \yii\helpers\VarDumper::dump($exist, 10, true);
////        echo '<hr>';
//        \yii\helpers\VarDumper::dump($temp, 10, true);

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
//        $i = 0;
//            $saw = new nokogiri(file_get_contents('http://kskforum.kz/forum/607-pksk-arman/'));
//
//        foreach ($saw->get('.col_c_forum') as $tds) {
//            $a = $tds['h4'][0]['a'][0];
//            if (strpos(mb_strtolower($a['#text'][0]), 'отчеты') !== false) {
//                echo $a['href'].'<hr>';
//            }
//        }
//            foreach ($saw->get('ol') as $ol) {
//                if (strpos($ol['class'], 'subforums') !== false) {
//                    $ok = true;
//                    $lis = $ol['li'];
////                    \yii\helpers\VarDumper::dump($ol, 10, true);
////                    echo "<hr>";
//
//                    foreach ($lis as $li) {
//                        $name = trim($li['a'][0]['title']);
//                        if (isset($li['a'][0]['font'])) {
//                            $name = trim($li['a'][0]['font'][0]['#text'][0]);
//                        }
//                        \yii\helpers\VarDumper::dump([
//                            $li['a'][0],
//                            $name,
//                            $li['a'][0]['href']
//                        ], 10, true);
//                        echo '<hr>';
////                        if (strpos($a['title'], '4 квартал') != false) {
////                            $exist[$item[0]] = $a['title'];
////                            break;
////                            echo '[\'' . $item[0] . '\', \'' . $a['title'] . '\'],';
////                        }
//        //                                echo '[\'' . $item[0] . '\', \'' . $a['title'] . '\', \'' . $a['href'] . '\'],';
//                    }
//
//                }
//            }


//        var elems = document.getElementsByTagName('ol');
//        var start = false;
//        var end = false;
//        for(i in elems) {
//            sub_elems = elems[i].getElementsByTagName('a');
//            for(j in sub_elems) {
//                text = sub_elems[j].innerHTML;
//                if (typeof sub_elems[j] == 'object') {
//                    exist = 0;
//                    if (text.indexOf('font') > -1) {
//                        exist = 1;
//                    }
//                    console.log('[\''+sub_elems[j].innerText+'\', \''+sub_elems[j].href+'\','+exist+'],');
//                }
//       }
//        }



        ?>

        <div id="info" class="panel panel-default">
            <div class="panel-heading">Информация</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tr>
                        <th>Наименование и адрес КСК</th>
                        <td id="address_ksk">А-3/5, АПК, Аксай-3, д. 10, кв. 5</td>
                    </tr>
                    <tr>
                        <th>Кол-во домов</th>
                        <td id="amount_house">5</td>
                    </tr>
                    <tr>
                        <th>Адреса домов</th>
                        <td id="address_house">Аксай-3, д. 2, 8-11</td>
                    </tr>
                    <tr>
                        <th>Ф.И.О. председателя</th>
                        <td id="fullname_chairman">Вергель Ольга Сергеевна</td>
                    </tr>
                    <tr>
                        <th>Телефон</th>
                        <td id="phone">р.т 231031, ав 231757</td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="doc" class="panel panel-default">
            <div class="panel-heading">Документы</div>
            <div class="panel-body" id="doc_body">
            </div>
        </div>
    </div>
</div>

<?php

$js = <<<JS
function getInfo(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/info/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                $('#address_ksk').html(data.data.address_ksk);
                $('#amount_house').html(data.data.amount_house);
                $('#address_house').html(data.data.address_house);
                $('#fullname_chairman').html(data.data.fullname_chairman);
                $('#phone').html(data.data.phone);
                $('#doc').hide();
                $('#info').show();
            }
        },'json');
}
function getDoc(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.post("/temp/default/doc/", {id: id,  _csrf:csrfToken},
        function (data) {
            if(data.success){
                console.log(data.data);
                $('#doc_body').html('');
                $.each(data.data, function(key, value) {
                    var path = value.substr(0,2)+'/'+value.substr(3,2)+'/'+value.substr(6,2)+'/'+value;
                    path = 'http://statics.ksk.local/attachments/store/' + path;
                    console.log(path);
                    $('#doc_body').append('<img src="'+path+'" style="width: 100%">')
                });
                $('#info').hide();
                $('#doc').show();
            }
        },'json');
}
JS;

$this->registerJS($js, \yii\web\View::POS_HEAD);

$js = <<<JS
$(document).ready(function() {
    console.log('ready');
    $('#info').hide();
    $('#doc').hide();
});
JS;

$this->registerJS($js, \yii\web\View::POS_READY);

?>
