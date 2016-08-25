<?php
class FastTemplate {

    var $FILELIST   =   array();    //  Holds the array of filehandles
                                    //  FILELIST[HANDLE] == "fileName"

    var $DYNAMIC    =   array();    //  Holds the array of dynamic
                                    //  blocks, and the fileHandles they
                                    //  live in.

    var $PARSEVARS  =   array();    //  Holds the array of Variable
                                    //  handles.
                                    //  PARSEVARS[HANDLE] == "value"

    var $LOADED     =   array();    //  We only want to load a template
                                    //  once - when it's used.
                                    //  LOADED[FILEHANDLE] == 1 if loaded
                                    //  undefined if not loaded yet.

    var $HANDLE     =   array();    //  Holds the handle names assigned
                                    //  by a call to parse()

    var $ROOT       =   "";         //  Holds path-to-templates

    var $WIN32      =   false;      //  Set to true if this is a WIN32 server

    var $ERROR      =   "";         //  Holds the last error message

    var $LAST       =   "";         //  Holds the HANDLE to the last
                                    //  template parsed by parse()

    var $STRICT     =   true;       //  Strict template checking.
                                    //  Unresolved vars in templates will
                                    //  generate a warning when found.
//by AiK
    var $start;                     // holds time of start generation


    /**
     * Constructor.
     * @param $pathToTemplates template root.
     * @return FastTemplate
     */
    function FastTemplate ($pathToTemplates = ""){
        global $php_errormsg;

        if(!empty($pathToTemplates)){
            $this->set_root($pathToTemplates);
        }
        // by AiK
        $this->start = $this->utime();
    }   // end (new) FastTemplate ()


   /**
    * Sets template root
    * All templates will be loaded from this "root" directory
    * Can be changed in mid-process by re-calling with a new
    * value.
    * @param $root path to templates dir
    * @return void
    */

    function set_root ($root){
        $trailer = substr($root,-1);

        if(!$this->WIN32){
            if( (ord($trailer)) != 47 ){
                $root = "$root". chr(47);
            }
            if(is_dir($root)){
                $this->ROOT = $root;
            }else{
                $this->ROOT = "";
                $this->error("Specified ROOT dir [$root] is not a directory");
            }
        }else{
            // WIN32 box - no testing
            if( (ord($trailer)) != 92 ){
                $root = "$root" . chr(92);
            }
            $this->ROOT = $root;
        }

    }   // End set_root()


   /**
    * Calculates current microtime
    * I throw this into all my classes for benchmarking purposes
    * It's not used by anything in this class and can be removed
    * if you don't need it.
    * @return void
    */

    function utime () {
        $time = explode( " ", microtime());
        $usec = (double)$time[0];
        $sec = (double)$time[1];
        return $sec + $usec;
    }

   /**
    * Strict template checking, if true sends warnings to STDOUT when
    * parsing a template with undefined variable references
    * Used for tracking down bugs-n-such. Use no_strict() to disable.
    * @return void
    */

    function strict (){
        $this->STRICT = true;
    }

   /**
    * Silently discards (removes) undefined variable references
    * found in templates
    * @return void
    */
    function no_strict (){
        $this->STRICT = false;
    }

    /**
    * A quick check of the template file before reading it.
    * This is -not- a reliable check, mostly due to inconsistencies
    * in the way PHP determines if a file is readable.
    * @return boolean
    */
    function is_safe ($filename){
        if(!file_exists($filename)){
            $this->error("[$filename] does not exist",0);
            return false;
        }
        return true;
    }

   /**
    * Grabs a template from the root dir and 
    * reads it into a (potentially REALLY) big string
    * @param $template template name
    * @return string
    */
    function get_template ($template){
        if(empty($this->ROOT)){
            $this->error("Cannot open template. Root not valid.",1);
            return false;
        }
        $filename   =   "$this->ROOT"."$template";
        $contents = implode("",(@file($filename)));
        if( (!$contents) or (empty($contents)) ){
            $this->error("+get_template() failure: [$filename] $php_errormsg",1);
        }
        return $contents;
    } // end get_template

   /**
    * Prints the warnings for unresolved variable references
    * in template files. Used if STRICT is true
    * @param $Line string for variable references checking
    * @return void
    */
    function show_unknowns ($Line){
        $unknown = array();
        if (preg_match("({[A-Z0-9_]+})",$Line,$unknown)) {
        	$UnkVar = $unknown[0];
            if(!(empty($UnkVar))){
				//@error_log("[FastTemplate] Warning: no value found for variable: $UnkVar ",0);
            }
        }
    }   // fend show_unknowns()

   /**  
    * This routine get's called by parse() and does the actual
    * {VAR} to VALUE conversion within the template.
    * @param $template string to be parsed
    * @param $tpl_array array of variables
    * @return string
    * @author CDI cdi@thewebmasters.net 
    * @author Artyem V. Shkondin artvs@clubpro.spb.ru 
    * @version 1.1.1
    */
    function parse_template ($template, $tpl_array){
        while ( list ($key,$val) = each ($tpl_array) ){
            if (!(empty($key))){
                if(gettype($val) != "string"){
                    settype($val,"string");
                }

        	//php4 doesn't like '{$' combinations.
        	$key = '{'."$key".'}';
        	//$template = ereg_replace("$key","$val","$template");
        	$template = preg_replace('{'.$key.'}',$val,$template);
            }
        }

        if(!$this->STRICT){
            // Silently remove anything not already found

            $template = ereg_replace("{([A-Z0-9_]+)}","",$template);
            
            //by AiK: remove dynamic blocks
            
                $lines = split("\n",$template);
                $inside_block = false;
                $template="";
               
            while (list ($num,$line) = each($lines) ){
                if (substr_count($line, "<!-- BEGIN DYNAMIC BLOCK:")>0 ){
                    $inside_block = true;
                }
                if (!$inside_block){
                    $template .= "$line\n";
                }
                if (substr_count($line, "<!-- END DYNAMIC BLOCK:")>0 ){
                    $inside_block = false;
                }
            } 
        }else{
            // Warn about unresolved template variables
            if (preg_match("({[A-Z0-9_]+})",$template)){
                $unknown = explode("\n",$template);
                while (list ($Element,$Line) = each($unknown) ){
                    $UnkVar = $Line;
                    if(!(empty($UnkVar))){
                        $this->show_unknowns($UnkVar);
                    }
                }
            }
        }
        return $template;

    }   // end parse_template();

    /**
     *  The meat of the whole class. The magic happens here.
     *  @param  $ReturnVar template handle
     *  @param  $template nick name
     *  @return void
     */

    function parse ( $ReturnVar, $FileTags ){
       
        $append = false;
        $this->LAST = $ReturnVar;
        $this->HANDLE[$ReturnVar] = 1;
        //echo "startparse $ReturnVar";
        if (gettype($FileTags) == "array"){
            unset($this->$ReturnVar);   // Clear any previous data
            while ( list ( $key , $val ) = each ( $FileTags ) ) {
                if ( (!isset($this->$val)) || (empty($this->$val)) ) {
                    $this->LOADED["$val"] = 1;
                    if(isset($this->DYNAMIC["$val"])){
                        $this->parse_dynamic($val,$ReturnVar);
                    }else{
                        $fileName = $this->FILELIST["$val"];
                        $this->$val = $this->get_template($fileName);
                    }
                }
                //  Array context implies overwrite
                $this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
                //  For recursive calls.
                $this->assign( array( $ReturnVar => $this->$ReturnVar ) );
            }
        }   // end if FileTags is array()
        else{
            // FileTags is not an array

            $val = $FileTags;

            if( (substr($val,0,1)) == '.' ){
                // Append this template to a previous ReturnVar

                $append = true;
                $val = substr($val,1);
            }
            if ( (!isset($this->$val)) || (empty($this->$val)) ){
                    $this->LOADED["$val"] = 1;
                    if(isset($this->DYNAMIC["$val"])) {
                        $this->parse_dynamic($val,$ReturnVar);
                    }else {
                        $fileName = $this->FILELIST["$val"];
                        $this->$val = $this->get_template($fileName);
                    }
            }
            if($append){
               // changed by AiK 
                if (isset($this->$ReturnVar)){
                    $this->$ReturnVar .= $this->parse_template($this->$val,$this->PARSEVARS);
                }else{
                    $this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);
                }
            }else{
                   $this->$ReturnVar = $this->parse_template($this->$val,$this->PARSEVARS);

            }

            //  For recursive calls.
            $this->assign(array( $ReturnVar => $this->$ReturnVar) );
        }
        return;
    }   //  End parse()

    /**
     * Prints parsed template
     * @param $template template handler
     * @return void 
     * @see FastTemplate#fetch()
     */

    function FastPrint ( $template = "" ){
        if(empty($template)){
            $template = $this->LAST;
        }

        if( (!(isset($this->$template))) || (empty($this->$template)) ) {
            $this->error("Nothing parsed, nothing printed",0);
            return;
        }else{
			$template = $this->$template;
			print $template;
        }
        return;
    }

    /**
     * Prints parsed template
     * @param $template template handler
     * @return parsed template 
     * @see FastTemplate#FastPrint()
     */

//  ************************************************************

    function fetch ( $template = "" )
    {
        if(empty($template))
        {
            $template = $this->LAST;
        }
        if( (!(isset($this->$template))) || (empty($this->$template)) )
        {
            $this->error("Nothing parsed, nothing printed",0);
            return "";
        }
		
        return($this->$template);
    }


//  ************************************************************

    function define_dynamic ($Macro, $ParentName)
    {
        //  A dynamic block lives inside another template file.
        //  It will be stripped from the template when parsed
        //  and replaced with the {$Tag}.

        $this->DYNAMIC["$Macro"] = $ParentName;
        return true;
    }

//  ************************************************************

    function parse_dynamic ($Macro,$MacroName)
    {
        // The file must already be in memory.
        //echo "parse_dynamic $Macro::$MacroName";
        $ParentTag = $this->DYNAMIC["$Macro"];
        if( (!isset($this->$ParentTag)) or (empty($this->$ParentTag)) )
        {
            $fileName = $this->FILELIST[$ParentTag];
            $this->$ParentTag = $this->get_template($fileName);
            $this->LOADED[$ParentTag] = 1;
        }
        if($this->$ParentTag)
        {
            $template = $this->$ParentTag;
            $DataArray = split("\n",$template);
            $newMacro = "";
            $newParent = "";
            $outside = true;
            $start = false;
            $end = false;
            while ( list ($lineNum,$lineData) = each ($DataArray) )
            {
                $lineTest = trim($lineData);
                if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = true;
                    $end = false;
                    $outside = false;
                }
                if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = false;
                    $end = true;
                    $outside = true;
                }
                if( (!$outside) and (!$start) and (!$end) )
                {
                    $newMacro .= "$lineData\n"; // Restore linebreaks
                }
                if( ($outside) and (!$start) and (!$end) )
                {
                    $newParent .= "$lineData\n"; // Restore linebreaks
                }
                if($end)
                {
                    $newParent .= '{'."$MacroName}\n";
                }
                // Next line please
                if($end) { $end = false; }
                if($start) { $start = false; }
            }   // end While

            $this->$Macro = $newMacro;
            $this->$ParentTag = $newParent;
            return true;

        }   // $ParentTag NOT loaded - MAJOR oopsie
        else
        {
            //@error_log("ParentTag: [$ParentTag] not loaded!",0);
            $this->error("ParentTag: [$ParentTag] not loaded!",0);
        }
        return false;
    }

//  ************************************************************
//  Strips a DYNAMIC BLOCK from a template.

    function clear_dynamic ($Macro="")
    {
        if(empty($Macro)) { return false; }

        // The file must already be in memory.

        $ParentTag = $this->DYNAMIC["$Macro"];

        if( (!$this->$ParentTag) or (empty($this->$ParentTag)) )
        {
            $fileName = $this->FILELIST[$ParentTag];
            $this->$ParentTag = $this->get_template($fileName);
            $this->LOADED[$ParentTag] = 1;
        }

        if($this->$ParentTag)
        {
            $template = $this->$ParentTag;
            $DataArray = split("\n",$template);
            $newParent = "";
            $outside = true;
            $start = false;
            $end = false;
            while ( list ($lineNum,$lineData) = each ($DataArray) )
            {
                $lineTest = trim($lineData);
                if("<!-- BEGIN DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = true;
                    $end = false;
                    $outside = false;
                }
                if("<!-- END DYNAMIC BLOCK: $Macro -->" == "$lineTest" )
                {
                    $start = false;
                    $end = true;
                    $outside = true;
                }
                if( ($outside) and (!$start) and (!$end) )
                {
                    $newParent .= "$lineData\n"; // Restore linebreaks
                }
                // Next line please
                if($end) { $end = false; }
                if($start) { $start = false; }
            }   // end While

            $this->$ParentTag = $newParent;
            return true;

        }   // $ParentTag NOT loaded - MAJOR oopsie
        else
        {
            //@error_log("ParentTag: [$ParentTag] not loaded!",0);
            $this->error("ParentTag: [$ParentTag] not loaded!",0);
        }
        return false;
    }

//  ************************************************************

    function define ($fileList)
    {
        while ( list ($FileTag,$FileName) = each ($fileList) )
        {
            $this->FILELIST["$FileTag"] = $FileName;
        }
        return true;
    }

//  ************************************************************

    function clear_parse ( $ReturnVar = "")
    {
        $this->clear($ReturnVar);
    }

//  ************************************************************

    function clear ( $ReturnVar = "" )
    {
        // Clears out hash created by call to parse()

        if(!empty($ReturnVar))
        {
            if( (gettype($ReturnVar)) != "array")
            {
                unset($this->$ReturnVar);
                return;
            }
            else
            {
                while ( list ($key,$val) = each ($ReturnVar) )
                {
                    unset($this->$val);
                }
                return;
            }
        }

        // Empty - clear all of them

        while ( list ( $key,$val) = each ($this->HANDLE) )
        {
            $KEY = $key;
            unset($this->$KEY);
        }
        return;

    }   //  end clear()

//  ************************************************************

    function clear_all ()
    {
        $this->clear();
        $this->clear_assign();
        $this->clear_define();
        $this->clear_tpl();

        return;

    }   //  end clear_all

//  ************************************************************

    function clear_tpl ($fileHandle = "")
    {
        if(empty($this->LOADED))
        {
            // Nothing loaded, nothing to clear

            return true;
        }
        if(empty($fileHandle))
        {
            // Clear ALL fileHandles

            while ( list ($key, $val) = each ($this->LOADED) )
            {
                unset($this->$key);
            }
            unset($this->LOADED);

            return true;
        }
        else
        {
            if( (gettype($fileHandle)) != "array")
            {
                if( (isset($this->$fileHandle)) || (!empty($this->$fileHandle)) )
                {
                    unset($this->LOADED[$fileHandle]);
                    unset($this->$fileHandle);
                    return true;
                }
            }
            else
            {
                while ( list ($Key, $Val) = each ($fileHandle) )
                {
                    unset($this->LOADED[$Key]);
                    unset($this->$Key);
                }
                return true;
            }
        }

        return false;

    }   // end clear_tpl

//  ************************************************************

    function clear_define ( $FileTag = "" )
    {
        if(empty($FileTag))
        {
            unset($this->FILELIST);
            return;
        }

        if( (gettype($Files)) != "array")
        {
            unset($this->FILELIST[$FileTag]);
            return;
        }
        else
        {
            while ( list ( $Tag, $Val) = each ($FileTag) )
            {
                unset($this->FILELIST[$Tag]);
            }
            return;
        }
    }

//  ************************************************************
//  Aliased function - used for compatibility with CGI::FastTemplate
/*    function clear_parse ()
    {
        $this->clear_assign();
    }
*/
//  ************************************************************
//  Clears all variables set by assign()

    function clear_assign ()
    {
        if(!(empty($this->PARSEVARS)))
        {
            while(list($Ref,$Val) = each ($this->PARSEVARS) )
            {
                unset($this->PARSEVARS["$Ref"]);
            }
        }
    }

//  ************************************************************

    function clear_href ($href)
    {
        if(!empty($href))
        {
            if( (gettype($href)) != "array")
            {
                unset($this->PARSEVARS[$href]);
                return;
            }
            else
            {
                while (list ($Ref,$val) = each ($href) )
                {
                    unset($this->PARSEVARS[$Ref]);
                }
                return;
            }
        }
        else
        {
            // Empty - clear them all

            $this->clear_assign();
        }
        return;
    }

//  ************************************************************

    /**
     * assign variables
     */
    function assign ($tpl_array, $trailer="")
    {
        if(gettype($tpl_array) == "array")
        {
            while ( list ($key,$val) = each ($tpl_array) )
            {
                if (!(empty($key)))
                {
                    //  Empty values are allowed
                    //  Empty Keys are NOT

                    $this->PARSEVARS["$key"] = $val;
                }
            }
        }
        else
        {
            // Empty values are allowed in non-array context now.
            if (!empty($tpl_array))
            {
                $this->PARSEVARS["$tpl_array"] = $trailer;
            }
        }
    }

//  ************************************************************
//  Return the value of an assigned variable.
//  Christian Brandel cbrandel@gmx.de

    function get_assigned($tpl_name = "")
    {
        if(empty($tpl_name)) { return false; }
        if(isset($this->PARSEVARS["$tpl_name"]))
        {
            return ($this->PARSEVARS["$tpl_name"]);
        }
        else
        {
            return false;
        }
    }

//  ************************************************************

    function error ($errorMsg, $die = 0)
    {
        $this->ERROR = $errorMsg;
        echo "ERROR: $this->ERROR <BR> \n";
        if ($die == 1)
        {
            exit;
        }

        return;

    } // end error()


//  ************************************************************

    /**
     *  Prints debug info into console
     * @return void
     * @author AiK
     * @since 1.1.1
     */
    function showDebugInfo(){
        $tm =  $this->utime()  - $this->start;
        // print time
        print "
        <SCRIPT language=javascript>
        _debug_console = window.open(\"\",\"console\",\"width=500,height=420,resizable,scrollbars=yes, top=0 left=130\"); 
        _debug_console.document.write('<HTML><TITLE>Debug Console</TITLE><BODY bgcolor=#ffffff>');
        _debug_console.document.write('<h3>Debugging info: generated during $tm seconds</h3>'); 
        ";    
        $this->printarray($this->FILELIST, "Templates");
        $this->printarray($this->DYNAMIC, "Dynamic bloks");
        $this->printarray($this->PARSEVARS, "Parsed variables");

       print " _debug_console.document.close();
       </SCRIPT> ";
    }//end of showDebugInfo()

    /**
     *
     */
     function printarray($arr,$caption){
     if (count($arr)!=0){
        print " 
        _debug_console.document.write('<font face=Tahoma color=#0000FF size=2><b>$caption</b> </font>');\n 
        _debug_console.document.write(\"<table border=0 width=100%  cellspacing=1 cellpadding=5>\");
        _debug_console.document.write('<tr bgcolor=#CCCCCC><th> key</th><th>value</th></tr>');\n ";
        $flag=true;
         while ( list ($key,$val) = each ($arr) ){
         $flag=!$flag;
             $val=htmlspecialchars(mysql_escape_string ($val));
         if (!$flag) {
            $color ="#EEFFEE";
         }else{
            $color ="#EFEFEF";
         }
            print "_debug_console.document.write('<tr bgcolor=$color><td> $key</td><td valign=bottom><pre>$val</pre></td></tr>');\n ";
         }
        print "_debug_console.document.write(\"</table>\");";
        }        
     }
//  ************************************************************
	// Clear empty un-assigned tags
	// @return void
	function clear_unassigned_tags() {
		
		preg_match_all("/{([A-Z0-9_]*)}/e", $this->FINAL, $tags);
		foreach ($tags[0] as $tag) {
			$this->FINAL = str_replace($tag, "", $this->FINAL);
		}
		
	}
//  ************************************************************

} // End class.FastTemplate.php
?>