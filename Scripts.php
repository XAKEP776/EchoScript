<?

/*Создаение подсветки*/
function createHglht($form){
		// Создаём подсветку, задаём цвета и обязательно имя
		$hi = new TSynPHPSyn( $form );
		$hi->parent = $form;
		$hi->name = 'SynPHPSyn';
		$hi->loadFromArray(
		    array (
		         'Comment' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 5283940 ),
		      'Identifier' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 13158600 ),
		             'Key' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 14064726 ),
		          'Number' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 11847740 ),
		           'Space' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 536870911 ),
		          'String' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 8756694 ),
		          'Symbol' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 13158600 ),
		        'Variable' => array ( 'style' => '', 'background' => 536870911, 'foreground' => 13132990 )
		    )
		);
}

/*Создаение новой страници*/
function createNewPage($pages,$caption,$i){
	$str = "object TabSheet$i: TTabSheet
		        object synEdit$i: TSynEdit
		          Left = 5
		          Top = 65
		          Width = 595
		          Height = 406
		          Color = 16777215
		          Align = alClient
		          Ctl3D = True
		          Color = 1973790
		          ParentCtl3D = False
		          Font.Charset = DEFAULT_CHARSET
		          Font.Color = clWindowText
		          Font.Height = -13
		          Font.Name = 'Courier New'
		          Font.Style = []
		          PopupMenu = Popup
		          TabOrder = 0
		          Gutter.Font.Charset = DEFAULT_CHARSET
		          Gutter.Font.Color = clWindowText
		          Gutter.Font.Height = -11
		          Gutter.Font.Name = 'Courier New'
		          Gutter.Font.Style = []
		          Gutter.ShowLineNumbers = True   
		          Highlighter = ES.SynPHPSyn
		          Options = [eoAutoIndent, eoDragDropEditing, eoDropFiles, eoEnhanceEndKey, eoGroupUndo, eoHalfPageScroll, eoScrollPastEof, eoShowScrollHint, eoSmartTabDelete, eoTabIndent, eoTabsToSpaces, eoTrimTrailingSpaces]
		          SelectedColor.Foreground = 11990266
		          WantTabs = True
		        end
		    end";

	$page = $pages->addPage($caption); 
    gui_readStr($page->self, $str);

    c("TabSheet$i.synEdit$i")->onMouseDown = function ($self){ 
        	                    if(get_key_state(2)<0){
                                    c("smenu")->popup( cursor_pos_x(), cursor_pos_y());
                                }
                            };
                 
	gui_propSet(c("TabSheet$i.synEdit$i")->gutter, 'Color', c("TabSheet$i.synEdit$i")->color);
	gui_propSet(c("TabSheet$i.synEdit$i")->gutter, 'BorderColor', 5723991);
	gui_propSet(c("TabSheet$i.synEdit$i")->gutter, 'LeftOffset',  6);
	gui_propSet(c("TabSheet$i.synEdit$i")->gutter, 'RightOffset', 6);
	gui_propSet(gui_propGet(c("TabSheet$i.synEdit$i")->gutter, 'Font'), 'Color', 13158600);

	return $page;
}

/*Создаение новой вкладки*/
function addPage($pages){
	global $data,$num;
	  $n = count($data);
	  $i = 0;

	  while($i<=$n){
         $num = $i+1;
           if($data[$i] == '' or $data[$i] == 0){
              $pages->activePage = createNewPage( $pages, "NewPage {$num}",$num);
              $data[$i] = 1;
           break;
           }elseif(!$data[$i]){
              $pages->activePage = createNewPage( $pages, "NewPage {$num}",$num);
              $data[] = 1;
           break;
           }
         $i++;
       }
}


/*Функции для попап меню вкладок*/
	function p_addPage(){
		addPage( c("pages1") );
	}

	function p_delPage(){
		global $data, $stat;
		$pageList = explode("\n", c("pages1")->pagesList);
		$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);

		$st = count($numNamePage);
		$deletePage = $numNamePage[$st-1];
		$deletePage = trim($deletePage);
		c("pages1")->delete( c("pages1")->pageIndex ) ;
		$data[$deletePage - 1] = 0;

	}	

/* Вперед */
function redoSyn(){ 
    $pageList = explode("\n", c("pages1")->pagesList);
	$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	$f = trim( $numNamePage[1]);
    c("TabSheet$f".".synEdit$f")->redo();
}

/* Назад */
function undoSyn(){ 
    $pageList = explode("\n", c("pages1")->pagesList);
	$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	$f = trim( $numNamePage[1]);
    c("TabSheet$f".".synEdit$f")->undo();
}

/* Вставить */
function pasteSyn(){ 
     $pageList = explode("\n", c("pages1")->pagesList);
   	 $numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	 $f = trim( $numNamePage[1]);
     c("TabSheet$f.synEdit$f")->pasteFromClipboard();
}

/* Скопировать */
function  copySyn(){ 
    $pageList = explode("\n", c("pages1")->pagesList);
	$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	$f = trim( $numNamePage[1]);
    c("TabSheet$f".".synEdit$f")->copyToClipboard(); 
}

/* Вырезать */
function CutSyn(){
	$pageList = explode("\n", c("pages1")->pagesList);
	$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	$f = trim( $numNamePage[1]);
    c("TabSheet$f".".synEdit$f")->cutToClipboard();
}

/* Выделить все */
function selectAll(){
    $pageList = explode("\n", c("pages1")->pagesList);
	$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	$f = trim( $numNamePage[1]);
    c("TabSheet$f".".synEdit$f")->selectAll();
}

function showSetting(){
	ShowForm(c('Setting'), SW_SHOW);
}


function getNameFilesDir(){
	dir_search("Style", $style, "ini", false, false);
	return $style;
}

function loadSettingGutter($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Gutter", "Numbering", $Numbering);
	if ( $Numbering == 1 ){
		c("checkbox1")->checked = true;
	}else{
		c("checkbox1")->checked = false;
	}
	

	ini::read("Gutter", "Color", $Color);
	c("e_color")->color = $Color;
	c("e_color")->text = $Color;


	ini::read("Gutter", "BruderColor", $BruderColor);
	c("e_colorBorder")->color = $BruderColor;
	c("e_colorBorder")->text = $BruderColor;

	ini::read("Gutter", "ColorFont", $ColorFont);
	c("e_fontcolor")->color = $ColorFont;
	c("e_fontcolor")->text = $ColorFont;


	ini::read("Gutter", "LeftOffset", $LeftOffset);
	c("leftoffset")->text = $LeftOffset;	


	ini::read("Gutter", "RightOffset", $rightoffset);
	c("rightoffset")->text = $rightoffset;

}

function getComment($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Comment", "background", $background );
	ini::read("Comment", "foreground", $foreground );
	ini::read("Comment", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}
}

function getIdentifier($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Identifier", "background", $background );
	ini::read("Identifier", "foreground", $foreground );
	ini::read("Identifier", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}
}

function getKey($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Key", "background", $background );
	ini::read("Key", "foreground", $foreground );
	ini::read("Key", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}
}

function getNumber($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Number", "background", $background );
	ini::read("Number", "foreground", $foreground );
	ini::read("Number", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}
}

function getSpace($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Space", "background", $background );
	ini::read("Space", "foreground", $foreground );
	ini::read("Space", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}
}

function getString($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("String", "background", $background );
	ini::read("String", "foreground", $foreground );
	ini::read("String", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}
}

function getSymbol($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Symbol", "background", $background );
	ini::read("Symbol", "foreground", $foreground );
	ini::read("Symbol", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}	
}

function getVariable($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Variable", "background", $background );
	ini::read("Variable", "foreground", $foreground );
	ini::read("Variable", "style", $style );
	c("e_background")->text = $background;
	c("e_background")->color = $background;

	c("e_foreground")->text = $foreground ;
	c("e_foreground")->color = $foreground ;

	if (preg_match("/\bfsBold\b/i", $style)) {
   			 c("checkbox2")->checked = true;
	}else{
			c("checkbox2")->checked = false;
	}
	if (preg_match("/\bfsItalic\b/i", $style)) {
			c("checkbox3")->checked = true;
	}else{
		c("checkbox3")->checked = false;
	}
	if (preg_match("/\bfsUnderline\b/i", $style)) {
			c("checkbox4")->checked = true;
	}else{
		c("checkbox4")->checked = false;
	}	
}

function getMainColor($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("main", "color", $color );
	c("e_mainColor")->color = $color;
	c("e_mainColor")->text = $color;
}


function loadPHPSyn($fileName){
	ini::open($fileName);//открываем файл с инлексом стиля
	ini::read("Comment", "background", $background1 );
	ini::read("Comment", "foreground", $foreground1 );
	ini::read("Comment", "style", $style1 );
	

	ini::read("Identifier", "background", $background2 );
	ini::read("Identifier", "foreground", $foreground2 );
	ini::read("Identifier", "style", $style2 );


	ini::read("Key", "background", $background3 );
	ini::read("Key", "foreground", $foreground3 );
	ini::read("Key", "style", $style3 );

	ini::read("Number", "background", $background4 );
	ini::read("Number", "foreground", $foreground4 );
	ini::read("Number", "style", $style4 );


	ini::read("Space", "background", $background5 );
	ini::read("Space", "foreground", $foreground5 );
	ini::read("Space", "style", $style5 );
	
	
	ini::read("String", "background", $background6 );
	ini::read("String", "foreground", $foreground6 );
	ini::read("String", "style", $style6 );

	ini::read("Symbol", "background", $background7 );
	ini::read("Symbol", "foreground", $foreground7 );
	ini::read("Symbol", "style", $style7 );


	ini::read("Variable", "background", $background8 );
	ini::read("Variable", "foreground", $foreground8 );
	ini::read("Variable", "style", $style8 );

	ini::read("main", "color", $color );
	c("synEdt")->color = $color;



	gui_readstr( c("Setting->synEdt")->self,
    'object SynEdt: TSynEdit
        Highlighter = Setting.SynTest
    end');

    c("Setting.SynTest")->loadFromArray(
		    array (
		         'Comment' => array ( 'style' => $style1, 'background' => $background1, 'foreground' => $foreground1 ),
		      'Identifier' => array ( 'style' => $style2, 'background' => $background2, 'foreground' => $foreground2 ),
		             'Key' => array ( 'style' => $style3, 'background' => $background3, 'foreground' => $foreground3 ),
		          'Number' => array ( 'style' => $style4, 'background' => $background4, 'foreground' => $foreground4 ),
		           'Space' => array ( 'style' => $style5, 'background' => $background5, 'foreground' => $foreground5 ),
		          'String' => array ( 'style' => $style6, 'background' => $background6, 'foreground' => $foreground6 ),
		          'Symbol' => array ( 'style' => $style7, 'background' => $background7, 'foreground' => $foreground7 ),
		        'Variable' => array ( 'style' => $style8, 'background' => $background8, 'foreground' => $foreground8 )
		    )
		);

}

function evalCode(){
	$pageList = explode("\n", c("pages1")->pagesList);
	$numNamePage = explode(" ",$pageList[c("pages1")->pageIndex]);
	$i = trim( $numNamePage[1]);


   $code = eval( c("TabSheet$i.synEdit$i")->text );
   pre( $code);
    
}
