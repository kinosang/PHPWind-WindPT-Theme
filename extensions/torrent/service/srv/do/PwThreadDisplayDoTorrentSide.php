<?php

defined('WEKIT_VERSION') || exit('Forbidden');

Wind::import('SRV:forum.srv.threadDisplay.do.PwThreadDisplayDoBase');
Wind::import('EXT:torrent.service.srv.helper.PwUtils');

class PwThreadDisplayDoTorrentSide extends PwThreadDisplayDoBase
{
    public function __construct() {}

    public function createHtmlAfterUserInfo($user, $read)
    {
        $showuserinfo = Wekit::C('site', 'app.torrent.showuserinfo');

        if (is_array($showuserinfo) && !in_array('threadside', $showuserinfo)) {
            return;
        }

        $torrents = Wekit::load('EXT:torrent.service.PwTorrent')->fetchTorrentByUid($user['uid']);
        $histories = Wekit::load('EXT:torrent.service.PwTorrentHistory')->fetchTorrentHistoryByUid($user['uid']);

        if (is_array($histories)) {
            foreach ($histories as $history) {
                $downloaded_total += $history['downloaded'];
                $uploaded_total += $history['uploaded'];
            }
        }

        if ($downloaded_total != 0) {
            $rotio = round($uploaded_total / $downloaded_total, 2);
        } else {
            $rotio = 'Inf.';
        }

        echo '<div id="PTInfo">下载： ' . PwUtils::readableDataTransfer($downloaded_total) . '<br>上传： ' . PwUtils::readableDataTransfer($uploaded_total) . '<br>分享率： ' . $rotio . '<br>发布： ' . count($torrents) . '</div>';
    }
}
