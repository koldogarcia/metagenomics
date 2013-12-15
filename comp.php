<?
/*
This script is to compare the output of metagenomic assignment programs
Copyright (C) 2011-2013 Koldo Garcia 

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

+Info --> koldo.garcia@ehu.es
*/

ini_set(max_execution_time, 0);
ini_set(memory_limit, -1);


$lines=explode("\n",file_get_contents("taxonomy.txt"));

foreach ($lines as $this_line)
{$fields=explode("\t",$this_line);
 $taxoload[$fields[0]]=$fields[1];
 $fields2=explode(";", $fields[1]);
 $taxoload2[trim($fields2[7])]=$fields[1];
 $fields3=explode(" ",trim($fields2[7]));
 $new_this_sp=$fields3[0]." ".$fields3[1];
 $taxoload3[$new_this_sp]=$fields[1];
}

$nb_file=$argv[1];
$lca_file=$argv[2];
$phymm_file=$argv[3];
$out_file=$argv[4];
$key_file=$argv[5];

$save="Phymm&NB&LCA\tPhymm&NB\tPhymm&LCA\tNB&LCA\tDifferent\tNoRank\tPhymm&NB\tDifferent\tTroublemakers\tNoRank\n";

if ($key_file!="")
{foreach(explode("\n",file_get_contents($key_file)) as $line)
 {$fields=explode("\t",$line);
  $levels=explode(";",$fields[1]);
  $orig_sp[$fields[0]]["phylum"]= trim($levels[0]);
  $orig_sp[$fields[0]]["class"]= trim($levels[1]);
  $orig_sp[$fields[0]]["order"]= trim($levels[2]);
  $orig_sp[$fields[0]]["family"]= trim($levels[3]);
  $orig_sp[$fields[0]]["genus"]= trim($levels[4]);
  $orig_sp[$fields[0]]["sp"]= trim($levels[5]);
 } 
}

$lines=explode("\n",file_get_contents($nb_file));
foreach ($lines as $this_line)
{$fields=explode("\t",$this_line);
 $fields2=explode(";", $fields[1]);
 $assig_nbbl[$fields[0]]["domain"]= trim($fields2[0]);
 $assig_nbbl[$fields[0]]["phylum"]= trim($fields2[1]);
 $assig_nbbl[$fields[0]]["class"]= trim($fields2[2]);
 $assig_nbbl[$fields[0]]["order"]= trim($fields2[3]);
 $assig_nbbl[$fields[0]]["family"]= trim($fields2[4]);
 $assig_nbbl[$fields[0]]["genus"]= trim($fields2[5]);
   
 if ($fields2[6]!="unclassified")
 {$fields3=explode(" ", $fields2[6]);
  if ($fields3[0]!="") $assig_nbbl[$fields[0]]["sp"]=$fields3[0]." ".$fields3[1];
  if ($fields3[0]=="") $assig_nbbl[$fields[0]]["sp"]=$fields3[1]." ".$fields3[2];
 }else
 {$assig_nbbl[$fields[0]]["sp"]=$fields2[7]; 
 }
}
 
$lines=explode("\n",file_get_contents($lca_file));
foreach ($lines as $this_line)
{$fields=explode("\t",$this_line);
 $fields2=explode(";", $fields[1]);
 $assig_lcaenb[$fields[0]]["domain"]= trim($fields2[0]);
 $assig_lcaenb[$fields[0]]["phylum"]= trim($fields2[1]);
 $assig_lcaenb[$fields[0]]["class"]= trim($fields2[2]);
 $assig_lcaenb[$fields[0]]["order"]= trim($fields2[3]);
 $assig_lcaenb[$fields[0]]["family"]= trim($fields2[4]);
 $assig_lcaenb[$fields[0]]["genus"]= trim($fields2[5]);
   
 if ($fields2[6]!="unclassified")
 {$fields3=explode(" ", $fields2[6]);
  if ($fields3[0]!="") $assig_lcaenb[$fields[0]]["sp"]=$fields3[0]." ".$fields3[1];
  if ($fields3[0]=="") $assig_lcaenb[$fields[0]]["sp"]=$fields3[1]." ".$fields3[2];
 }else
 {$assig_lcaenb[$fields[0]]["sp"]=$fields2[7]; 
 } 
}

$lines=explode("\n",file_get_contents($phymm_file));
foreach ($lines as $this_line)
{$fields=explode("\t",$this_line);
 $seq_num++;
   
 if (trim($fields[11])!="") 
 {if ($fields[11]=="Crenarchaeota" or $fields[11]=="Euryarchaeota" or $fields[11]=="Korarchaeota" or $fields[11]=="Nanoarchaeota" or $fields[11]=="Thaumarchaeota")  
  {$assig_phymm[$fields[0]]["domain"]= "Archaea";
  }else
  {$assig_phymm[$fields[0]]["domain"]= "Bacteria";  
  }
    
  $assig_phymm[$fields[0]]["phylum"]= trim($fields[11]);
  $assig_phymm[$fields[0]]["class"]= trim($fields[9]);
  $assig_phymm[$fields[0]]["order"]= trim($fields[7]);
  $assig_phymm[$fields[0]]["family"]= trim($fields[5]);
  $assig_phymm[$fields[0]]["genus"]= trim($fields[3]);  
  $fields3=explode("_", $fields[1]);
    
  if ($fields3[0]!="" and $fields3[0]!=$fields3[1]) 
  {$assig_phymm[$fields[0]]["sp"]=$fields3[0]." ".$fields3[1];
  }else
  {$assig_phymm[$fields[0]]["sp"]=$fields3[1]." ".$fields3[2];
  }
 }else  
 {$fields2=explode(";", $taxoload[$fields[1]]);
  $assig_phymm[$fields[0]]["domain"]= trim($fields2[0]);
  $assig_phymm[$fields[0]]["phylum"]= trim($fields2[1]);
  $assig_phymm[$fields[0]]["class"]= trim($fields2[2]);
  $assig_phymm[$fields[0]]["order"]= trim($fields2[3]);
  $assig_phymm[$fields[0]]["family"]= trim($fields2[4]);
  $assig_phymm[$fields[0]]["genus"]= trim($fields2[5]);
    
  if ($assig_phymm[$fields[0]]["domain"]=="Virus")  
  {$assig_phymm[$fields[0]]["sp"]=$fields2[6];
  }else
  {$fields3=explode(" ", $fields2[6]);
   if ($fields3[0]!="" and $fields3[0]!=$fields3[1]) 
   {$assig_phymm[$fields[0]]["sp"]=$fields3[0]." ".$fields3[1];
   }else
   {$assig_phymm[$fields[0]]["sp"]=$fields3[1]." ".$fields3[2];
   } 
  }
 } 
}

foreach ($assig_phymm as $read => $assig_read)
{foreach ($assig_read as $this_taxa => $null)
 {$read_fields=explode("_",$read);
  unset($majority);
  if ($assig_phymm[$read][$this_taxa]==$assig_nbbl[$read][$this_taxa] or $assig_phymm[$read][$this_taxa]==$assig_lcaenb[$read][$this_taxa]) $majority=$assig_phymm[$read][$this_taxa];
  if ($assig_nbbl[$read][$this_taxa]==$assig_lcaenb[$read][$this_taxa]) $majority=$assig_nbbl[$read][$this_taxa];
  if ($majority=="") $majority="NO";
  $out[$this_taxa].="$read\t".$assig_phymm[$read][$this_taxa]."\t".$assig_nbbl[$read][$this_taxa]."\t".$assig_lcaenb[$read][$this_taxa]."\t".$majority."\t".$orig_sp[$read_fields[0]][$this_taxa]."\n";
 }
}
 
foreach($out as $this_taxa => $tofile)
{$ft=fopen($out_file."_".$this_taxa."_assign.txt","w");
 fwrite($ft, "read\tPhymmBL\tBayesian\tLCA\tOriginal\n".$tofile);
 fclose($ft);
}
unset($out);

foreach ($assig_phymm as $read => $assig_read)
{$read_fields=explode("_",$read);
 foreach ($assig_read as $this_taxa => $null)
 {if ($assig_lcaenb[$read][$this_taxa]!="unclassified")
  {$tri[$this_taxa]["total"]++;
   if ($assig_read[$this_taxa]==$assig_nbbl[$read][$this_taxa] and $assig_read[$this_taxa]==$assig_lcaenb[$read][$this_taxa] and $assig_lcaenb[$read][$this_taxa]==$assig_nbbl[$read][$this_taxa] and trim($assig_read[$this_taxa])!="" and trim($assig_nbbl[$read][$this_taxa])!="" and trim($assig_lcaenb[$read][$this_taxa])!="") 
   {$tri[$this_taxa]["pnblca"]++;
    $same[$this_taxa][$assig_read[$this_taxa]]++;
    if ($key_file!="")
    {if ($orig_sp[$read_fields[0]][$this_taxa]==$assig_read[$this_taxa]) $welldone[$this_taxa]["pnblca"]++;
     if ($orig_sp[$read_fields[0]][$this_taxa]!=$assig_read[$this_taxa]) $baddone[$this_taxa]["pnblca"]++;
    }
   }elseif($assig_read[$this_taxa]==$assig_nbbl[$read][$this_taxa] and trim($assig_read[$this_taxa])!="" and trim($assig_nbbl[$read][$this_taxa])!="")
   {$tri[$this_taxa]["pnb"]++;
    $same[$this_taxa][$assig_read[$this_taxa]]++;  
    if ($key_file!="")
    {if ($orig_sp[$read_fields[0]][$this_taxa]==$assig_read[$this_taxa]) $welldone[$this_taxa]["pnb"]++;
     if ($orig_sp[$read_fields[0]][$this_taxa]!=$assig_read[$this_taxa]) $baddone[$this_taxa]["pnb"]++;
    }
   }elseif($assig_read[$this_taxa]==$assig_lcaenb[$read][$this_taxa] and trim($assig_read[$this_taxa])!="" and trim($assig_lcaenb[$read][$this_taxa])!="")
   {$tri[$this_taxa]["plca"]++;
    $same[$this_taxa][$assig_read[$this_taxa]]++;
    if ($key_file!="")
    {if ($orig_sp[$read_fields[0]][$this_taxa]==$assig_read[$this_taxa]) $welldone[$this_taxa]["plca"]++;
     if ($orig_sp[$read_fields[0]][$this_taxa]!=$assig_read[$this_taxa]) $baddone[$this_taxa]["plca"]++;
    }
   }elseif($assig_lcaenb[$read][$this_taxa]==$assig_nbbl[$read][$this_taxa] and trim($assig_nbbl[$read][$this_taxa])!="" and trim($assig_lcaenb[$read][$this_taxa])!="")
   {$tri[$this_taxa]["nblca"]++;
    $same[$this_taxa][$assig_nbbl[$read][$this_taxa]]++;
    if ($key_file!="")
    {if ($orig_sp[$read_fields[0]][$this_taxa]==$assig_lcaenb[$read][$this_taxa]) $welldone[$this_taxa]["nblca"]++;
     if ($orig_sp[$read_fields[0]][$this_taxa]!=$assig_lcaenb[$read][$this_taxa]) $baddone[$this_taxa]["nblca"]++;
    }
   }elseif(trim($assig_read[$this_taxa])!="" and trim($assig_nbbl[$read][$this_taxa])!="" and trim($assig_lcaenb[$read][$this_taxa])!="")
   {$tri[$this_taxa]["no"]++;
    $nosame_p[$this_taxa][$assig_read[$this_taxa]]++;
    $nosame_nb[$this_taxa][$assig_nbbl[$read][$this_taxa]]++;
    $baddone[$this_taxa]["no"]++;
   }else
   {$tri[$this_taxa]["norank"]++;
    if ($key_file!="")
    {if ($orig_sp[$read_fields[0]][$this_taxa]==$assig_lcaenb[$read][$this_taxa] and $orig_sp[$read_fields[0]][$this_taxa]==$assig_nbb[$read][$this_taxa] and $orig_sp[$read_fields[0]][$this_taxa]==$assig_read[$this_taxa]) $welldone[$this_taxa]["norank"]++;
     if ($orig_sp[$read_fields[0]][$this_taxa]!=$assig_lcaenb[$read][$this_taxa] or $orig_sp[$read_fields[0]][$this_taxa]!=$assig_nbbl[$read][$this_taxa] or $orig_sp[$read_fields[0]][$this_taxa]!=$assig_read[$this_taxa]) $baddone[$this_taxa]["norank"]++;
    }
   }  
  }elseif($assig_nbbl[$read][$this_taxa]!="unclassified")
  {$pnb[$this_taxa]["total"]++;
   if($assig_read[$this_taxa]==$assig_nbbl[$read][$this_taxa] and trim($assig_read[$this_taxa])!="" and trim($assig_nbbl[$read][$this_taxa])!="")
   {$pnb[$this_taxa]["pnb"]++;
    $same[$this_taxa][$assig_read[$this_taxa]]++;
    if ($key_file!="")
    {if ($orig_sp[$read_fields[0]][$this_taxa]==$assig_read[$this_taxa]) $welldone[$this_taxa]["pnb2"]++;
     if ($orig_sp[$read_fields[0]][$this_taxa]!=$assig_read[$this_taxa]) $baddone[$this_taxa]["pnb2"]++;
    }
   }elseif (trim($assig_read[$this_taxa])!="" and trim($assig_nbbl[$read][$this_taxa])!="")  
   {$pnb[$this_taxa]["no"]++;
    $nosame_p[$this_taxa][$assig_read[$this_taxa]]++;
    $nosame_nb[$this_taxa][$assig_nbbl[$read][$this_taxa]]++;
    if ($key_file!="") $baddone[$this_taxa]["no2"]++;
    if ($this_taxa=="genus")
    {if($assig_nbbl[$read][$this_taxa]=="Clostridium") $geni["Clos"]++;
     if($assig_read[$this_taxa]=="Streptococcus") $geni["Strep"]++;      
     if($assig_read[$this_taxa]=="Yersinia") $geni["Yersinia"]++;      
     if($assig_read[$this_taxa]=="Rickettsia") $geni["Rick"]++;      
     if($assig_nbbl[$read][$this_taxa]=="Rhodococcus") $geni["Rhodo"]++;     
     if($assig_read[$this_taxa]=="Chlamydophila") $geni["Chlamy"]++;
     if($assig_read[$this_taxa]=="Borrelia") $geni["Borrelia"]++;
     if($assig_nbbl[$read][$this_taxa]=="Campylobacter") $geni["Campy"]++;
     if($assig_read[$this_taxa]=="Corynebacterium") $geni["Coryne"]++;
     if($assig_read[$this_taxa]=="Mycobacterium") $geni["Mycobac"]++;
     if($assig_read[$this_taxa]=="Mycoplasma") $geni["Mycoplas"]++;
    }
   }else
   {$pnb[$this_taxa]["norank"]++;   
    if ($key_file!="")
    {if (trim($orig_sp[$read_fields[0]][$this_taxa])==trim($assig_read[$this_taxa]) and trim($orig_sp[$read_fields[0]][$this_taxa])==trim($assig_nbbl[$read][$this_taxa])) $welldone[$this_taxa]["norank2"]++;
     if (trim($orig_sp[$read_fields[0]][$this_taxa])!=trim($assig_read[$this_taxa]) or trim($orig_sp[$read_fields[0]][$this_taxa])!=trim($assig_nbbl[$read][$this_taxa])) $baddone[$this_taxa]["norank2"]++;
    }
   }
  }else 
  {$p[$this_taxa]["total"]++; 
  }  
 } 
}

$level_order=array('domain','phylum','class','order','family','genus','sp');
 
if ($key_file!="")
{$out_well="Phymm&NB&LCA\tPhymm&NB\tPhymm&LCA\tNB&LCA\tDifferent\tNoRank\tPhymm&NB\tDifferent\tNoRank\n";
 $out_bad="Phymm&NB&LCA\tPhymm&NB\tPhymm&LCA\tNB&LCA\tDifferent\tNoRank\tPhymm&NB\tDifferent\tNoRank\n";
 
 $cats=array('pnblca', 'pnb', 'plca', 'nblca', 'no', 'norank', 'pnb2', 'no2', 'norank2');
 
 foreach ($level_order as $this_taxa)
 {foreach ($cats as $this_cat)
  {if ($welldone[$this_taxa][$this_cat]=="") $welldone[$this_taxa][$this_cat]=0;
   if ($baddone[$this_taxa][$this_cat]=="") $baddone[$this_taxa][$this_cat]=0;
  }
  
  $out_well.="$this_taxa\t".$welldone[$this_taxa]["pnblca"]."\t".$welldone[$this_taxa]["pnb"]."\t".$welldone[$this_taxa]["plca"]."\t".$welldone[$this_taxa]["nblca"]."\t".$welldone[$this_taxa]["no"]."\t".$welldone[$this_taxa]["norank"]."\t".$welldone[$this_taxa]["pnb2"]."\t".$welldone[$this_taxa]["no2"]."\t".$welldone[$this_taxa]["norank2"]."\n";
  $out_bad.="$this_taxa\t".$baddone[$this_taxa]["pnblca"]."\t".$baddone[$this_taxa]["pnb"]."\t".$baddone[$this_taxa]["plca"]."\t".$baddone[$this_taxa]["nblca"]."\t".$baddone[$this_taxa]["no"]."\t".$baddone[$this_taxa]["norank"]."\t".$baddone[$this_taxa]["pnb2"]."\t".$baddone[$this_taxa]["no2"]."\t".$baddone[$this_taxa]["norank2"]."\n";
 }
}

foreach ($tri as $this_taxa => $tri_d)
{if ($this_taxa!="")
 {if ($this_taxa=="genus")
  {foreach ($geni as $num)
   {$pnb["genus"]["trouble"]+=$num;
   }
   $pnb["genus"]["no"]-=$pnb["genus"]["trouble"];
  }
  
  if ($tri_d["pnblca"]=="") $tri_d["pnblca"]=0;
  if ($tri_d["pnb"]=="") $tri_d["pnb"]=0;
  if ($tri_d["plca"]=="") $tri_d["plca"]=0;
  if ($tri_d["nblca"]=="") $tri_d["nblca"]=0;
  if ($tri_d["no"]=="") $tri_d["no"]=0;
  if ($tri_d["norank"]=="") $tri_d["norank"]=0;
  if ($pnb[$this_taxa]["pnb"]=="") $pnb[$this_taxa]["pnb"]=0;
  if ($pnb[$this_taxa]["no"]=="") $pnb[$this_taxa]["no"]=0;
  if ($pnb[$this_taxa]["trouble"]=="") $pnb[$this_taxa]["trouble"]=0;
  if ($pnb[$this_taxa]["norank"]=="") $pnb[$this_taxa]["norank"]=0;
  
  $save.=$this_taxa."\t".$tri_d["pnblca"]."\t".$tri_d["pnb"]."\t".$tri_d["plca"]."\t".$tri_d["nblca"]."\t".$tri_d["no"]."\t".$tri_d["norank"]."\t".$pnb[$this_taxa]["pnb"]."\t". $pnb[$this_taxa]["no"]."\t". $pnb[$this_taxa]["trouble"]."\t". $pnb[$this_taxa]["norank"]."\n";
  arsort($same[$this_taxa]);
  $save2.="$this_taxa\n";
  $savesort.="Same $this_taxa\n";
  unset($j, $k, $l);
  
  foreach ($same[$this_taxa] as $this_taxa2 => $num)
  {$per=round(($num/$seq_num)*10000)/100;
   $save2.="$this_taxa2\t$num\t$per\n";
   $not_n2+=$num;
   $j++;
  
   if ($j<=10) 
   {$savesort.="$j) $this_taxa2 $num ($per)\n";
   }else
   {$k++;
    $l+=$num;
   }
  }
  
  $per=round(($l/$seq_num)*10000)/100;
  $not_n=$seq_num-$not_n2;
  $per_not_n=round(($not_n/$seq_num)*10000)/100;
  $savesort.="Other $k $l ($per)\nNot assigned $not_n ($per_not_n)\n\n\n";
  $save2.="Not assigned\t$not_n\t$per_not_n\n\n\n";
 
  arsort($nosame_p[$this_taxa]);
  $save3.="$this_taxa\n";
  $savesort.="Phymm $this_file $this_taxa\n";
  unset($j, $k, $l, $not_n2); 
 
  foreach ($nosame_p[$this_taxa] as $this_taxa2 => $num)  
  {$per=round(($num/$seq_num)*10000)/100;   
   $save3.="$this_taxa2\t$num\t$per\n";   
   $j++;

   if ($j<=10) 
   {$savesort.="$j) $this_taxa2 $num ($per)\n";
   }else
   {$k++;
    $l+=$num;
   }
  }
  
  $per=round(($l/$seq_num)*10000)/100;
  $savesort.="Other $k $l ($per)\n\n\n";
  $save3.="\n\n";
  
  arsort($nosame_nb[$this_taxa]);
  $save4.="$this_taxa\n";
  $savesort.="NB $this_taxa\n";
  unset($j, $k, $l);
  
  foreach ($nosame_nb[$this_taxa] as $this_taxa2 => $num)
  {$per=round(($num/$seq_num)*10000)/100;
   $save4.="$this_taxa2\t$num\t$per\n";
   $j++;
   
   if ($j<=10) 
   {$savesort.="$j) $this_taxa2 $num ($per)\n";
   }else
   {$k++;
    $l+=$num;
   }
  }
  
  $per=round(($l/$seq_num)*10000)/100;
  $savesort.="Other $k $l ($per)\n\n\n";
  $save4.="\n\n";
  unset($j, $k, $l);  
 }
}

$ft=fopen($out_file."_comp.txt","w+"); 
fwrite($ft, $save);
fclose($ft);

$ft=fopen($out_file."_common.txt","w+");
fwrite($ft, $save2);
fclose($ft);

$ft=fopen($out_file."_diff_phymm.txt","w+");
fwrite($ft, $save3);
fclose($ft);

$ft=fopen($out_file."_diff_nb.txt","w+");
fwrite($ft, $save4);
fclose($ft);

$ft=fopen($out_file."_sorted.txt","w+");
fwrite($ft, $savesort);
fclose($ft);

if ($key_file!="")
{$ft=fopen($out_file."_well.txt","w+");
 fwrite($ft, $out_well);
 fclose($ft);
 
 $ft=fopen($out_file."_bad.txt","w+");
 fwrite($ft, $out_bad);
 fclose($ft);
}

?>
