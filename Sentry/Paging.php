<?php

require_once 'SystemComponent.php';
require_once 'functions.php';

class MySQLPagedResultSet extends SystemComponent {

  var $results;
  var $pageSize;
  var $page;
  var $row;

  function MySQLPagedResultSet($query,$pageSize)
  {

  $settings = SystemComponent::getSettings();

  $dns = $settings['systemdns'];
  $user = $settings['dbusername'];
  $pass = $settings['dbpassword'];

  $this->link = odbc_connect($dns, $user, $pass);

  $cnx = $this->link;
  //register_shutdown_function(array(&$this, 'close'));

    $resultpage = $_GET['resultpage'];

    $this->results = @odbc_exec($query,$cnx);
    $this->pageSize = $pageSize;
    if ((int)$resultpage <= 0) $resultpage = 1;
    if ($resultpage > $this->getNumPages())
      $resultpage = $this->getNumPages();
    $this->setPageNum($resultpage);
  }

  function getNumPages()
  {
    if (!$this->results) return FALSE;

    return ceil(odbc_num_rows_erm($this->results) /
                (float)$this->pageSize);
  }

  function setPageNum($pageNum)
  {
    if ($pageNum > $this->getNumPages() or
        $pageNum <= 0) return FALSE;

    $this->page = $pageNum;
    $this->row = 0;
    odbc_data_source($this->results,($pageNum-1) * $this->pageSize);
  }

  function getPageNum()
  {
    return $this->page;
  }

  function isLastPage()
  {
    return ($this->page >= $this->getNumPages());
  }

  function isFirstPage()
  {
    return ($this->page <= 1);
  }

  function fetchArray()
  {
    if (!$this->results) return FALSE;
    if ($this->row >= $this->pageSize) return FALSE;
    $this->row++;
    return odbc_fetch_array($this->results);
  }

  function getPageNav($queryvars = '')
  {
    $nav .= "<p class=\"paging\" wrap=\"wrap\">";
    if (!$this->isFirstPage())
    {
      $nav .= "<span class=\"nextprev\" title=\"Previous\"><a href=\"?resultpage=".
              ($this->getPageNum()-1).'&'.$queryvars.'">&laquo;</a></span>&nbsp;';
    }
    if ($this->getNumPages() > 1)
      for ($i=1; $i<=$this->getNumPages(); $i++)
      {
        if ($i==$this->page)
          $nav .= "<span class=\"pagecurrent\" title=\"$i\">$i</span>&nbsp;";
        else
          $nav .= "<span class=\"pagelink\" title=\"$i\"><a href=\"?resultpage={$i}&".
                  $queryvars."\">{$i}</a></span>&nbsp;";
      }
    if (!$this->isLastPage())
    {
      $nav .= "<span class=\"nextprev\" title=\"Next\"><a href=\"?resultpage=".
              ($this->getPageNum()+1).'&'.$queryvars.'">&raquo;</a></span>';
    }
    $nav .= "</p>";
    return $nav;
  }
}

?>