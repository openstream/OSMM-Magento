Overview
========

Here is Magento version of [Open-Social-Media-Monitoring project](http://openstream.github.com/open-social-media-monitoring/). It is absolutely self-contained and requires no additional libraries to be installed. This extension allows you to monitor social networks activity by defined keyword(s). Unlimited number of tracking campaigns with any number of keywords per campaign can be created. Currently the list of supported social networks consists of Twitter and Facebook. Each keyword can be limited by language and/or geo location (currently supported for Twitter only).

Compatibility
=============

Extension is compatible with Magento 1.4.2.0 and later.

Installation
============

1. Make sure your [Magento cron jobs](http://www.magentocommerce.com/wiki/1_-_installation_and_configuration/how_to_setup_a_cron_job) are running.
2. Upload all files preserving directory structure.
3. Clear Magento cache. Log out of your Magento interface and then back in in order to reset ACLs.

Configuration
=============

Extension configuration screen can be found at System\Configuration\Reports\Social Media Monitoring. Configuration values are:

1. **Keep Timeline For**. Specifies number of days after which timeline will be archived. In order to keep database size in reasonable measures extension archives timeline results which are more then X days old. Archived entries will be still considered in graph and top influencers calculations but will not be visible in a row stream carousel anymore.
2. **Show Disabled Campaigns**. Specifies if disabled campaigns have to be displayed at the dashboard. Normally disabling campaign stops collecting new data. Additionally you can control if already  collected data should be accessible or not.
3. **Alchemy API Key**. Unfortunately Facebook API is not filtering search results by language so Alchemy service is used to detect a language of Facebook post. If you are not using language filter for your keywords you can leave it blank. Otherwise please obtain a [Alchemy API Key](http://www.alchemyapi.com/api/register.html).
4. **Convert Non-ASCII Characters**. Some databases like MySQL before version 5.5.3 are not allowing to store some non-latin characters. Turning this option on will convert all non-latin characters into their HTML representation before storing them to database.

Usage
=====

Once you log into Magento administrator interface you will notice a new tab at your dashboard called `Social Media Monitoring`. It has no data yet because you have no campaigns in your system. To add a campaign go to Reports\Social Media Monitoring\Campaigns. It may be a good idea to add some keywords into the system first. To add a keyword go to Reports\Social Media Monitoring\Keywords.
