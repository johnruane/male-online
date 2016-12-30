<?php

// $bad_words = ['ageless','ample','assets','boob','braless','bust','busty','cleavage','curves','enviable','endless legs','eye-popping','figure-hugging','flat stomach','flashes','flashing','flaunt','flaunts','fuller','gushes','gym','leggy','midriff','perky','pert','pins','plunging','postirior','pout','racy','revealing','saucy','scantly','scanty','sexy','showcase','showcases','sideboob','sizable','sizzle','sizzles','sizzling','skimpy','skin-tight','skinny','slim','slender','steamy','super-slim','surgically-enhanced','thigh','teases','toned','trim','underboob','yummy','vamp'];

function getLinks($url, $query) {
    $link_results = array();
    $html = file_get_contents($url);
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DomXpath($dom);

    $links = $xpath->query($query);

    foreach ($links as $article) {
    	$node = $xpath->query("descendant::a/attribute::href", $article);
    	array_push($link_results, "http://www.dailymail.co.uk" . $node->item(0)->textContent);
    }
    return $link_results;
}

function queryLinks($ary_of_links) {
    $query_results = array();
    $bad_words = ['TENNIS','TENNIS'];

    foreach ($ary_of_links as $link) {
        $html = file_get_contents($link);
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $xpath = new DomXpath($dom);

        $articles = $xpath->query('//ul[contains(concat(" ", normalize-space(@class), " "), " archive-articles ")]/li');

        foreach ($articles as $article) {

            $node = $xpath->query("descendant::a", $article);
            $node_text = $node->item(0)->textContent;

            foreach ($bad_words as $word) {
                if ( stripos("/\b".$node_text."\b/i", $word) ) {

                    array_push($frequencey, strtolower($word));

                    $result['text'] = $node_text;

                    $node = $xpath->query("descendant::a/attribute::href", $article);
                    $result['link'] = $node->item(0)->nodeValue;

                    if ( array_key_exists ( $word , $results ) ) {
                        array_push($query_results[$word], $result);
                    } else {
                        $query_results[$word][] = $result;
                    }
                }
            }
        }
    }
    return $query_results;
}

class GuestBook {
	//Total number of entries in guestbook this number is updated everytime new entry is added or deleted


		var $total=0;


		var $entry_id=0;
		var $entry_author='';
		var $entry_email='';
		var $entry_url='http://';
		var $entry_dob;
		var $entry_location;
		var $referer='';
		var $entry_date;
		var $entry_comments='';
		var $entry_ip='0.0.0.0';
		var $entry_hidden='0';
			/* this variable is used to hide, unhide a specific entry.
				by default the entry is viewable to everybody */

        function GuestBook() {
			// constructor can be used later
				$this->update_total();


		}




		function update_total() {
			$sql="select * from ".ENTRY_TABLE." WHERE entry_hidden='0'";
			$this->total=$GLOBALS["db"]->num_rows($GLOBALS["db"]->query($sql));

		}



	    function get($field) {
			return($this->$field);
        }

        function set($field,$value) {
			$this->$field=$value;
        }


        function retrieve_entry($id) {
			$sql="select * from ".ENTRY_TABLE." where entry_id=".$id;
			$result=$GLOBALS["db"]->query($sql);
            if($result && ($GLOBALS["db"]->num_rows($result)==1 )) {
				$data=$GLOBALS["db"]->fetch_object($result);
				$this->entry_id=$data->entry_id;
 	            $this->entry_dob=$data->entry_dob;
        		$this->entry_location=$data->entry_location;
  		        $this->entry_author=$data->entry_author;
				$this->entry_email=$data->entry_email;
				$this->entry_url=$data->entry_url;
				$this->entry_comments=$data->entry_comments;
				$this->entry_date=$data->entry_date;
				$this->referer=$data->entry_referer;
				$this->entry_ip=$data->entry_ip;
				$this->entry_hidden=$data->entry_hidden;
				return true;

			} else {
					// no entry with such id exist
					return false;

     		}
			return false;

		}











		function display_n_entries($start=-1,$end=-1) {
        /* This function can be used to display all the entries in the guestbook
           or can be called with proper $start and $end values to return only
           specific no. of entries.
           when called with $start =-1 & $end =-1 will display all the entries
           on a single page
           */



		    // print "inside display n entries";
		    if($start==-1 && $end==-1) {


			$sql= "Select * from ".ENTRY_TABLE." where entry_hidden='0' ORDER BY ENTRY_DATE desc";

		    }
		    else {
		       $sql= "Select * from ".ENTRY_TABLE." where entry_hidden='0' ORDER BY ENTRY_DATE desc limit $start, $end";

		    }
		   // print "<br>$sql<br>";
			$result=$GLOBALS['db']->query($sql);
		    print("<div class='display_entries'>\n");

		          $class="even";
			while($data=$GLOBALS['db']->fetch_object($result)) {
			        print "<div class='".$class."entry'>\n";
						print"<div class='".$class."entry_top_row'>";
						      print("<div class='topquestion'>&nbsp;&nbsp;&nbsp;Name</div>\n");
			                  print("<div class='topresponse'>&nbsp;".$data->entry_author."</div>\n");
						print"</div>\n";

						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;ID</div>\n");
						      print("<div class='".$class."response'>&nbsp;".$data->entry_id."</div>\n");
			            print("</div>\n");

					 if(trim($data->entry_dob)!="") {
						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Birthday</div>\n");
						      print("<div class='".$class."response'>&nbsp;".$data->entry_dob."</div>\n");
			            print("</div>\n");
					 }

					if(trim($data->entry_location)!="") {
						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Location</div>\n");
						      print("<div class='".$class."response'>&nbsp;".$data->entry_location."</div>\n");
			            print("</div>\n");
					}
					if(trim($data->entry_email)!="") {
						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Email</div>\n");
						      print("<div class='".$class."response'>&nbsp;<a href='mailto:".$data->entry_email."' class='link'>".$data->entry_email."</a></div>\n");
			            print("</div>\n");
					}

		        	 if(trim($data->entry_url)!=""  && trim($data->entry_url)!="http://") {
						 print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Website</div>\n");
						      print("<div class='".$class."response'>&nbsp;<a href='".$data->entry_url."' class='link' target='_blank'>".$data->entry_url."</a></div>\n");
			            print("</div>\n");
			         }
                    if(trim($data->entry_referer)!="") {
						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Knew about site from </div>\n");
						      print("<div class='".$class."response'>&nbsp;".$data->entry_referer."</div>\n");
			            print("</div>\n");
			        }
			        if(trim($data->entry_comments)!="") {
						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Comments</div>\n");
						      print("<div class='".$class."response'>&nbsp;".$data->entry_comments."</div>\n");
			            print("</div>\n");
			        }

						print("<div class='".$class."'>\n\n");
							  print("<div class='".$class."question'>&nbsp;&nbsp;&nbsp;Signed On</div>\n");
						      print("<div class='".$class."response'>&nbsp;".$data->entry_date."</div>\n");
			            print("</div>\n");
			            if($class=="even")
						     $class="odd";
	                     else if($class=="odd")
				           $class="even";
				print"&nbsp;</div>\n";
			}
            print"     </div>\n";



		}

		function delete_entry($id) {
		// function to delete an entry

				$sql="delete from ".ENTRY_TABLE." where entry_id=".$id;
				if($GLOBALS["db"]->query($sql))
			   {
					$this->update_total();
					return true;

			   } else {
				   $this->update_total();
				   return false;
               }
         }

		function add_entry() {

		    // update existing user's data
		    // do not use update() had password been changed,
			// instead, use password()

			//INSERT INTO `entry_detail` ( `entry_id` , `entry_author` , `entry_dob` , `entry_email` , `entry_url` //, `entry_location` , `entry_date` , `entry_comment` , `entry_referer` )
			$sql = "INSERT INTO ".ENTRY_TABLE." "
					." (entry_id, entry_author,entry_dob, entry_email, entry_url, entry_location, entry_date, entry_comments, entry_referer,entry_ip,entry_hidden) "
					." VALUES ($this->entry_id,"
                                . "'$this->entry_author',"
								. "'$this->entry_dob',"
								. "'$this->entry_email',"
							   	. "'$this->entry_url',"
								. "'$this->entry_location',"
					     		. "NOW(),"
								. "'$this->entry_comments',"
								. "'$this->referer',"
								. "'$this->entry_ip',"
								. "'$this->entry_hidden'"
							." ) ";
				//print "\n$sql\n";

				$GLOBALS["db"]->query($sql);
				$this->update_total();
		 }


		function hide_entry($id) {
				$ret=false;
				$sql = "update ".ENTRY_TABLE."	set entry_hidden='1' where entry_id=".$id;
				if($GLOBALS['db']->query($sql)) {
					$ret=true;
					$this->update_total();
				}
				return $ret;


		}

		function unhide_entry($id) {
				$ret=false;
				$sql = "update ".ENTRY_TABLE."	set entry_hidden='0' where entry_id=".$id;
				if($GLOBALS['db']->query($sql)) {
					$ret=true;
					$this->update_total();
				}
				return $ret;


		}



		function modify_entry() {

			$sql = "UPDATE ".ENTRY_TABLE." "
				 . " SET"
			  	 . " entry_dob = '$this->entry_dob',"
                 . " entry_location = '$this->entry_location',"
                 . " entry_author = '$this->entry_author',"
				 . " entry_url = '$this->entry_url',"
			     . " entry_comments = '$this->entry_comments',"
				 . " entry_email = '$this->entry_email',"
			     . " entry_referer = '$this->referer'"
				 . " where entry_id=$this->entry_id";
			//print"<BR>$sql<br>";
				$GLOBALS["db"]->query($sql);
		}


		function next_id(){
			$sql="Select max(entry_id) from ".ENTRY_TABLE;
			$data=mysql_fetch_array($GLOBALS["db"]->query($sql));
			$id=$data[0];
			//print "<br>id = ".$id."<br>";
			return ++$id;
		}



		function display_add_form($error="") {
			if(trim($error)!="") {
				print"<div class='error'>Please correct following errors:<br>$error</div>\n";

			}
			//print"this is the add form total current entries = ".$GLOBALS["gb"]->total;

			print " <div class='add_entry'>
					<form action='{$_SERVER['PHP_SELF']}' method=\"post\">

					    <div class='add_row'>&nbsp;
				           <div class='label'> Name * </div>
				           <div class='value'>
				           <input class='text' name='entry_name' type='text' size='35' value='{$this->entry_author}'>
				           </div>
				        </div>

					    <div class='add_row'>&nbsp;
				            <div class='label'> Email * </div>
				           <div class='value'>
			                <input class='text' name='entry_email' type='text' size='35' value='{$this->entry_email}'>
				            </div>
				        </div>

					    <div class='add_row'>&nbsp;
						     <div class='label'> Location </div>
						     <div class='value'>
					         <input class='text' name='entry_location' type='text' size='35' value='{$this->entry_location}'>
						     </div>
					    </div>

						<div class='add_row'>&nbsp;
						     <div class='label'> Birthday </div>
						     <div class='value'>
					         <input class='text' name='entry_dob' type='text' size='35' value='{$this->entry_dob}'>
						     </div>
					    </div>

					    <div class='add_row'>&nbsp;
					         <div class='label'>Knew abt this site from? </div>
					         <div class='value'>
					         <input class='text' name='entry_referer' type='text' size='35' value='{$this->referer}'>
						     </div>
						</div>

						<div class='add_row'>&nbsp;
					        <div class='label'> Website(if any) </div>
						    <div class='value'>
				            <input class='text' name='entry_website' type='text' size='35'
value='{$this->entry_url}'>
					        </div>
					    </div>

						<div class='add_row'>&nbsp;
					        <div class='label'> Message/Comments *</div>
					        <div class='value'>
					        <textarea class='text' name='entry_comments' cols='30' rows='7' class='tbl4' value='{$this->entry_comments}'></textarea>
					        </div>
					    </div>

						<div class='add_row'>&nbsp;
					        <div  class='label'>
					        <input class='text' name='submit' type='submit' value='Submit' class='btn'>

					        </div>
					    </div>

					<input type='hidden' name='mode' value='2'>
				  </form>
				&nbsp;</div>			";





		}

}
?>
