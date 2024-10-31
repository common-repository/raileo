<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       raileo
 * @since      1.0.0
 *
 * @package    Raileo
 * @subpackage Raileo/admin/partials
 */

function showError($msg) {
    ?>
    <div class="error notice is-dismissible">
        <p><?php print $msg ?></p>
    </div>
    <?php
} 
function showFooter() {
    ?>
        <div class="raileo-links">
            <h2>Looking for help?</h2>
            <p>We have some resources that can help you</p>
            <ul>
                <li>
                    <a href="https://docs.raileo.com/" target="_blank" title="Raileo documentation">Documentation</a>
                </li>
                <li>
                    <a href="https://docs.raileo.com/faqs" target="_blank" title="Raileo FAQs">Frequently asked questions</a>
                </li>
                <li>
                    <a href="https://raileo.com?ref=wordpress_plugin" target="_blank" title="Raileo Website">Raileo.com</a>
                </li>
                <li>
                    <a href="https://status.raileo.com/" target="_blank" title="Raileo system status">Status</a>
                </li>
            </ul>
            <p style="border-top: 1px solid #ddd; padding-top: 10px">Found a bug? Please write to hi@raileo.com</p>
        </div>
    <?php
}
function raileo_page() {
    ?>
    <div class="section group">
        <div class="col span_1_of_2">
            <?php
            welcomeMessage();
            showApiKeyForm();
            showUrlsTable();
            ?>
        </div>
        <div class="col span_2_of_2">
            <?php
                showFooter();
            ?>
        </div>
    </div> <?php
} 
function welcomeMessage() {
    ?>
    <h1 class="page-title">
        Raileo for WordPress
    </h1>
    <?php
}
//api key form
function showApiKeyForm() {
    $savedRaileoApiKey = '';
    $raileo = new Raileo();
    if(array_key_exists('raileo_api_key_submit', $_POST)) {
        $raileoApiKey = sanitize_text_field($_POST['raileo_api_key']);
        if($raileoApiKey !== '' && strlen($raileoApiKey) !== 62) { //validate the length
            showError('Invalid API Key');
        } else {
            $raileoApiKey = filter_var($raileoApiKey, FILTER_SANITIZE_STRING);
            $result = $raileo->saveApiKey($raileoApiKey);
        }
    }
    $savedAaileoApiKey = $raileo->getApiKey();
?>
    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="raileo_api_key_submit">Status</label></th>
                    <td>
                        <?php
                            if($savedAaileoApiKey !== '')
                                print '<span class="status positive">Connected</span>';
                            else 
                                print '<span class="status">Not Connected</span>';
                        ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="raileo_api_key_submit">API Key</label>
                    </th>
                    <td>
                        <input type="text" value="<?php print $savedAaileoApiKey ?>" class="widefat" placeholder="Your Raileo API key" id="raileo_api_key" name="raileo_api_key">
                        <p class="help">
                            The API key for connecting with your Raileo account. <a target="_blank" href="https://raileo.com/admin/api_keys">Get your API key here.</a>
                        </p>

                    </td>

                </tr>

            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="raileo_api_key_submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
    <?php
}
//refresh urls table
if(array_key_exists('refresh_url_details', $_POST)) {
    $raileo = new Raileo();
    $urls = $raileo->getUrls(true);
}
//construct urls table
function showUrlsTable() {
    $raileo = new Raileo();
    $urls = $raileo->getUrls();
    if(isset ($urls->data) && count($urls->data) > 0) {
        print '<div class="header-with-action"><h3>Your URLs in Raileo</h3>'
    ?>
        <form action="" method="post">
            <button type="submit" name="refresh_url_details" class="button"> <?php print 'Refresh URLs' ?> </button>
        </form>
        </div>
        <p>Below are the URLs you have created in Raileo Dashboard. If you wish to edit/delete URL, please visit 
            <a href="https://raileo.com/admin/urls" target="_blank" title="Raileo Dashboard">Raileo Dashboard. </a>
            Click any URL Name to get more details about their monitoring.
        </p>
        <table class="widefat striped" style="margin-bottom: 30px;">
            <thead>
                <tr>
                    <td>URL Name</td>
                    <td>URL</td>
                    <td>Created At</td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($urls->data as $url) {
                    ?>
                        <tr>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="raileo_url_id" value="<?php print $url->id ?> ">
                                    <button type="submit" name="fetch_url_details" class="raileo-link-button"> <?php print $url->url_name ?> </button>
                                </form>
                            </td>
                            <td><?php print $url->url ?></td>
                            <td><?php print $url->created_at ?></td>
                        </tr>
                    <?php
                } ?>
            </tbody>
        </table>
    <?php
    if(array_key_exists('fetch_url_details', $_POST)) {
        $raileo = new Raileo();
        $urlId = sanitize_text_field($_POST['raileo_url_id']);
        $urlId = (int)$urlId;
        if($urlId !== 0) {
            $urlDetails = $raileo->fetchUrlData($urlId);
            showUrlDetails($urlDetails->data);
        } else {
            print '<p><strong>That\'s an invalid Url ID</strong></p>';    
        }
    }
    } else {
        print '<p><strong> There are no URLs available in Raileo. </strong></p>';
        print '<p> Make sure API Key is correct and you have added your URL in <a href="https://raileo.com/admin/urls">Raileo Dashboard</a></p>';
    }
}
// show monitoring details for a specific url
function showUrlDetails($data) {
    ?> 
    <h3 style="margin-bottom: 0"> Monitoring data for <?php print $data->url ?> </h3>
    <table class="widefat striped raileo-urls-data-table">
        <tbody>
            <!-- uptime monitoring data -->
            <tr>
                <td>Uptime Status</td>
                <td>
                    <?php 
                        if(isset($data->latest_uptime_check)){
                            if($data->latest_uptime_check->ping_status < 399){
                                print '<span class="score good"></span>';
                                print 'Your website is online. Last checked at '.$data->latest_uptime_check->last_checked;
                            } else {
                                print '<span class="score low"></span>';
                                print 'Your website is offline. Last checked at '.$data->latest_uptime_check->last_checked;
                            }
                        } else {
                            print '<span class="score default"></span>';
                            print 'No data available';
                        }
                    ?>
                </td>
            </tr>
            <!-- ssl monitoring data -->
            <tr>
                <td>SSL Status</td>
                <td>
                    <?php 
                        if(isset($data->latest_ssl_check)){ 
                            if($data->latest_ssl_check->expires_in != ''){
                                if($data->latest_ssl_check->expires_in > 30) {
                                    print '<span class="score good"></span>';
                                } else {
                                    print '<span class="score low"></span>';
                                }
                                print 'Website SSL expires in  '.$data->latest_ssl_check->expires_in. ' days. ('.$data->latest_ssl_check->expiry_date.'). ';
                            }
                        } else {
                            print '<span class="score default"></span>';
                            print 'No data available. ';
                        }
                        if($data->latest_ssl_check->is_monitoring_active == 0) {
                            print 'SSL Monitoring is not active';
                        }
                    ?>
                </td>
            </tr>
            <!-- pagespeed monitoring data -->
            <tr>
                <td>Pagespeed Scan</td>
                <td>
                    <?php 
                        if(isset($data->latest_pagespeed_report)){
                            if($data->latest_pagespeed_report->device_type != ''){
                                ?>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Checked on <?php print $data->latest_pagespeed_report->device_type ?>
                                                    at <?php print $data->latest_pagespeed_report->created_at ?> UTC
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Performance Score</td>
                                                <td><?php print $data->latest_pagespeed_report->performance_score ?></td>
                                            </tr>
                                            <tr>
                                                <td>SEO Score</td>
                                                <td><?php print $data->latest_pagespeed_report->seo_score ?></td>
                                            </tr>
                                            <tr>
                                                <td>Accessibility Score</td>
                                                <td><?php print $data->latest_pagespeed_report->accessibility_score ?></td>
                                            </tr>
                                            <tr>
                                                <td>Best Practices Score</td>
                                                <td><?php print $data->latest_pagespeed_report->best_practices_score ?></td>
                                            </tr>
                                            <tr>
                                                <td>PWA Score</td>
                                                <td><?php print $data->latest_pagespeed_report->pwa_score ?></td>
                                            </tr>
                                            <tr>
                                                <td>First contentful Paint</td>
                                                <td><?php print $data->latest_pagespeed_report->first_contentful_paint ?></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="<?php print 'https://raileo.com/admin/pagespeedreports/'.$data->latest_pagespeed_report->id ?>" target="_blank">View the complete report</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                            }
                            else {
                                print 'No pagespeed data available';
                            }
                        } else {
                            print 'No data available';
                        }
                    ?>
                </td>
            </tr>

        </tbody>
    </table>
    <?php
}
