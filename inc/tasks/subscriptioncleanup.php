<?php
/**
 * Subscription cleanup task
 * Copyright 2010 Starpaul20
 */

function task_subscriptioncleanup($task)
{
	global $mybb, $db;

	// Delete old thread subscriptions
	if($mybb->settings['threadsubscriptioncut'] > 0)
	{
		$cut = TIME_NOW-($mybb->settings['threadsubscriptioncut']*60*60*24);
		$query = $db->simple_select("threads", "tid, lastpost");
		while($old_sub = $db->fetch_array($query))
		{
			if($old_sub['lastpost'] < $cut)
			{
				$db->delete_query("threadsubscriptions", "tid='{$old_sub['tid']}'");
			}
		}
	}

	// Delete old forum subscriptions
	if($mybb->settings['forumsubscriptioncut'] > 0)
	{
		$cut = TIME_NOW-($mybb->settings['forumsubscriptioncut']*60*60*24);
		$query = $db->simple_select("forums", "fid, lastpost");
		while($old_fsub = $db->fetch_array($query))
		{
			if($old_fsub['lastpost'] < $cut)
			{
				$db->delete_query("forumsubscriptions", "fid='{$old_fsub['fid']}'");
			}
		}
	}

	add_task_log($task, "The subscription cleanup task successfully ran.");
}
?>