<?php 

$dir = dirname(__FILE__) . '/';
$wgAutoloadClasses['SponsorshipDashboard']	= $dir . 'SponsorshipDashboard.body.php';
$wgAutoloadClasses['SponsorshipDashboardService'] = $dir . 'SponsorshipDashboardService.class.php';

// report class
$wgAutoloadClasses['SponsorshipDashboardReports'] = $dir . 'SDReports.class.php';
$wgAutoloadClasses['SponsorshipDashboardReport'] = $dir . 'SDReport.class.php';
$wgAutoloadClasses['SponsorshipDashboardGroups'] = $dir . 'SDGroups.class.php';
$wgAutoloadClasses['SponsorshipDashboardGroup'] = $dir . 'SDGroup.class.php';
$wgAutoloadClasses['SponsorshipDashboardUsers'] = $dir . 'SDUsers.class.php';
$wgAutoloadClasses['SponsorshipDashboardUser'] = $dir . 'SDUser.class.php';

// report sources
$wgAutoloadClasses['SponsorshipDashboardSourceGapi'] = $dir . 'sources/SDSourceGapi.class.php';
$wgAutoloadClasses['SponsorshipDashboardSourceGapiCu'] = $dir . 'sources/SDSourceGapiCu.class.php';
$wgAutoloadClasses['SponsorshipDashboardSourceStats'] = $dir . 'sources/SDSourceStats.class.php';
$wgAutoloadClasses['SponsorshipDashboardSourceOneDot'] = $dir . 'sources/SDSourceOneDot.class.php';
$wgAutoloadClasses['SponsorshipDashboardSourceMobile'] = $dir . 'sources/SDSourceMobile.class.php';
$wgAutoloadClasses['SponsorshipDashboardSource'] = $dir . 'sources/SDSource.class.php';
$wgAutoloadClasses['SponsorshipDashboardSourceDatabase'] = $dir . 'sources/SDSourceDatabase.class.php';

// date provider classes
$wgAutoloadClasses['SponsorshipDashboardDateProvider'] = $dir . 'SDDateProvider.class.php';
$wgAutoloadClasses['SponsorshipDashboardDateProviderDay'] = $dir . 'SDDateProvider.class.php';
$wgAutoloadClasses['SponsorshipDashboardDateProviderMonth'] = $dir . 'SDDateProvider.class.php';
$wgAutoloadClasses['SponsorshipDashboardDateProviderYear'] = $dir . 'SDDateProvider.class.php';

// output formater classes
$wgAutoloadClasses['SponsorshipDashboardOutputFormatter'] = $dir . 'output/SDOutputFormatter.class.php';
$wgAutoloadClasses['SponsorshipDashboardOutputChart'] = $dir . 'output/SDOutputChart.class.php';
$wgAutoloadClasses['SponsorshipDashboardOutputTable'] = $dir . 'output/SDOutputTable.class.php';
$wgAutoloadClasses['SponsorshipDashboardOutputCSV'] = $dir . 'output/SDOutputCSV.class.php';

// Ajax
$wgAutoloadClasses[ 'SponsorshipDashboardAjax' ] = $dir . 'SponsorshipDashboardAjax.class.php';

$wgAutoloadClasses['gapi'] = $dir . '../../../lib/gapi/gapi.class.php';