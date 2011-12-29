<?php


class selectWidget
{
  var $destination = '';
  var $imgurl = '';
  var $showCamera = true;
  var $showRADAR = true;
  var $showWarnings = true;
  var $map = '';
  var $imgsz_y = 500;
  var $imgsz_x = 400;
  var $selectedSite = '';
  var $showDMX = false;

  function selectWidget($nwnpath, $mydestination)
  {
    $this->map = ms_newMapObj("$nwnpath/data/GIS/base.map");
	$this->map->set("width", 400);
	$this->map->set("height", 500);
    $this->extents = Array(320000, 4490000, 554000, 4765000);
    $this->fullextents = Array(320000, 4490000, 554000, 4765000);
	$this->map->setextent(320000, 4490000, 554000, 4765000);
    $this->destination = $mydestination;

  }
  function logic($f)
  {
    /** If zoom is set, then we need to do a map operation */
    if (isset($f["zoom"]))
    {
      /** If zoom is 100, we go to full-view */
      if ($f["zoom"] == 100)
      {
        $this->extents = $this->fullextents;
        $this->drawMap();
      }
      /** If zoom is 0, we are querying! */
      else if ($f["zoom"] == 0)
      {
        $this->performQuery($f);
      }
      else
      {
        $this->click2geo($f["extents"], $f["map_x"], $f["map_y"], $f["zoom"]);
        $this->drawMap();
      }
    }
    else {
        $this->drawMap();
    }
  }

  function click2geo($oextents, $click_x, $click_y, $zoom)
        {
        //echo "+++ click2geo +++<br>";
        $arExtents = explode(",", $oextents);
        $ll_x = $arExtents[0];
        $ll_y = $arExtents[1];
        $ur_x = $arExtents[2];
        $ur_y = $arExtents[3];
//  print_r($arExtents);
 
        $dy = ($ur_y - $ll_y) / floatval($this->imgsz_y);
        $dx = ($ur_x - $ll_x) / floatval($this->imgsz_x);
 
        $centerX = ($click_x * $dx) + $ll_x ;
        $centerY = $ur_y - ($click_y * $dy) ;
 
        if (intval($zoom) < 0)
    $zoom = -1 / intval($zoom) ;
                                                                                
        $n_ll_x = $centerX - (($dx * $zoom) * ($this->imgsz_x / 2.00));
        $n_ur_x = $centerX + (($dx * $zoom) * ($this->imgsz_x / 2.00));
        $n_ll_y = $centerY - (($dy * $zoom) * ($this->imgsz_y / 2.00));
        $n_ur_y = $centerY + (($dy * $zoom) * ($this->imgsz_y / 2.00));
 
        $this->extents = Array($n_ll_x, $n_ll_y, $n_ur_x, $n_ur_y);
  }



  function performQuery($form)
  {
    if (! isset($form["map_x"]) ) return;
    /** Get the click event from the form */
    $click_x = $form["map_x"];
    $click_y = $form["map_y"];
    
    if (isset($form["extents"])) $arExtents = explode(",", $form["extents"]);
    else $arExtents = $this->fullextents;
        $ll_x = $arExtents[0];
        $ll_y = $arExtents[1];
        $ur_x = $arExtents[2];
        $ur_y = $arExtents[3];

    
    $dy = ($ur_y - $ll_y) / floatval($this->imgsz_y);
    $dx = ($ur_x - $ll_x) / floatval($this->imgsz_x);
 
    $centerX = ($click_x * $dx) + $ll_x ;
    $centerY = $ur_y - ($click_y * $dy) ;
 

    $click = ms_newPointObj();
    $click->setXY($centerX, $centerY);

    $sites = $this->map->getlayerbyname("sites");
    $sites->set("status", MS_ON);
    $sites->queryByPoint($click, MS_SINGLE, -1);
    $sites->open();
    $rs = $sites->getresult(0);
    $shp = $sites->getShape(-1,  $rs->shapeindex);

    $this->selectedSite = $shp->values["SID"];

  }
  function drawMap()
  {
    $this->map->setextent($this->extents[0], $this->extents[1],
                          $this->extents[2], $this->extents[3]);
    $sites = $this->map->getlayerbyname("sites");
    $sites->set("status", MS_ON);
    if (! $this->showCamera)
    {
      $sites_c0 = $sites->getClass(0);
      $sites_c0->setexpression("ZZZZZ");
    }
	$warnings = $this->map->getlayerbyname("warnings_c");
	$warnings->set("status", MS_ON);

    $counties = $this->map->getlayerbyname("counties");
    $counties->set("status", MS_ON);
    
    $doppler8 = $this->map->getlayerbyname("doppler8");
    $doppler8->set("status", MS_ON);
	$lclass = $doppler8->getClass(0);
    $lstyle = $lclass->getStyle(0);
    $lstyle->set("size", 35);
    $pt = ms_newpointobj();
    $pt->setXY(300, 30);
                                                                                
    $radar = $this->map->getlayerbyname("KCCI");
    if ($this->showDMX){ $radar = $this->map->getlayerbyname("wsr88d"); }
    $radar->set("status", MS_ON);

    $img = $this->map->prepareImage();
    if ($this->showRADAR) $radar->draw($img);
    $counties->draw($img);
    $sites->draw($img);
    if ($this->showRADAR && $this->showKCCI){
      $pt->draw($this->map, $doppler8, $img, "logo", "");
    }
	if ($this->showWarnings) $warnings->draw($img);

    if ($this->showRADAR && $this->showKCCI){
      $fc =  file('/home/ldm/data/gis/images/26915/KCCI/KCCI_N0R_tm_0.txt');
      $ts = strtotime($fc[0]);
      $credits = $this->map->getLayerByName("credits");
      $c = $credits->getClass(0);
      $c->label->set("size", "14");
      $point = ms_newpointobj();
      $point->setXY($this->imgsz_x - 75, 10);
      putenv("TZ=CST6CDT");
      $point->draw($this->map, $credits, $img, "credits", strftime("%I:%M %p"));
      //$point->draw($this->map, $credits, $img, "credits", strftime("%I:%M %p", $ts) );
    }

    $this->map->drawLabelCache($img);

    $this->imgurl = $img->saveWebImage();

  }
  function setShowCamera($choice)
  {
    $this->showCamera = $choice;
  }
  function setShowRADAR($choice)
  {
    $this->showRADAR = $choice;
    if ($choice == true)
    {
      $fc =  file('/home/ldm/data/gis/images/26915/KCCI/KCCI_N0R_tm_0.txt');
      $diff = time() - strtotime($fc[0]);
      $this->showKCCI = true;
      if ($diff > 500) 
      { 
        $this->showKCCI = false;
        $this->showDMX = true;
      }
    }
  }
  function setShowWarnings($choice)
  {
    $this->showWarnings = $choice;
  }
 
} // End of selectWidget
