<?php if ( !empty( $this->messages ) ): ?>
	<div class="updated" style="clear:both"><p><?php echo $this->messages; ?></p></div>
<?php endif; ?>

<br/>

<br/>

<?php echo do_shortcode( '[cminds_free_guide id="authorizedstore"]' ); ?>

<br/>
<div class="clear"></div>

<form method="post">
	<?php wp_nonce_field( 'update-options' ); ?>
    <input type="hidden" name="action" value="update" />


    <div id="cmtt_tabs" class="glossarySettingsTabs">
        <div class="glossary_loading"></div>

        <div id="tabs-1">
            <div class="block">
                <h3>General Settings</h3>
                <div style="background-color:#e5ffe5; padding:10px;">
               <h4>Phase I: Vetting Process</h4>
               <p>Unique Retailer/Store seal code is required, for seal to appear. Seal shows “Inactive” in red, until Retailer/Store is vetted, approved and current on required fees.</p>
                <ul>
                <li>1 - Go to: <a href="https://www.authorizedstore.com/Online-Stores/SignUp" target="new">Apply/SignUp</a> at Authorized Store</li>
                <li>2 - Complete Retailer/Store application.</li>
                <li>3 - <a href="https://www.authorizedstore.com/Online-Stores/Vetting_Process" target="new">Pay</a> the one time Vetting Fee. (See: Vetting Process/Fees)</li>
                </ul>

               <h4>Phase II: Seal Selection</h4>
                <p>Once vetting process is complete and your store is approved, you’ll be prompted by
                e-mail to pay monthly recurring fee. When fee is paid, account and seal become “Active”.</p>
                <ul>
                <li>4 - <a href="https://www.authorizedstore.com/Account/Login" target="new">Login</a> to Authorized Store Retailer Interface. Choose Seal Configuration tool. Select seal size and color. </li>
                <li>5 - Copy unique code and paste it in the “Seal HTML Code” location of the extension/plugin.</li>
            </ul>

            <p><strong>Note:</strong> Extension/plugin seal location placement is limited to float left or right. Developer skills required for alternative, non-float seal code placement options.</p>
        </div>
                <table class="floated-form-table form-table">
                    <tr valign="top">
                        <th scope="row">Seal HTML Code</th>
                        <td>
							<textarea name="authstore_sealCode" rows="5" cols="60"><?php echo $this->get_option( 'authstore_sealCode' ); ?></textarea>
                        </td>
						<?php $exampleCode = "<div class='asSeal-xxxxxxx-xxxx-...' data-sealuid='xxxxxxx-xxxx-...'>
<script src='https://www.authorizedstore.com/Certificates/StoreCertificateJavaScript/xxxxxxx-xxxx-...' type='text/javascript'></script>
</div>"; ?>
                        <td colspan="2" class="cmtt_field_help_container">Retrieve unique code, using seal configuration tool in Authorized Store Retailer Interface.<br /> Code looks like this: <pre style="font-size: 8pt"><?php echo esc_html($exampleCode); ?></pre></td>
                    </tr>
                    <tr valign="top" class="whole-line">
                        <th scope="row">Seal Placement Option?</th>
                        <td>
							<select name="authstore_sealPosition">
								<option value="none" <?php echo selected( 'none', $this->get_option( 'authstore_sealPosition' ) ); ?>>None</option>
								<option value="top-right" <?php echo selected( 'top-right', $this->get_option( 'authstore_sealPosition' ) ); ?>>Top-Right</option>
								<option value="bottom-right" <?php echo selected( 'bottom-right', $this->get_option( 'authstore_sealPosition' ) ); ?>>Bottom-Right</option>
								<option value="bottom-left" <?php echo selected( 'bottom-left', $this->get_option( 'authstore_sealPosition' ) ); ?>>Bottom-Left</option>
								<option value="top-left" <?php echo selected( 'top-left', $this->get_option( 'authstore_sealPosition' ) ); ?>>Top-Left</option>
							</select>
                        </td>
                        <td colspan="2" class="cmtt_field_help_container">Select bottom corner location for "Floating" type seal.<br /> Additional location options require a developer for specific code placement.</td>
                    </tr>
                </table>
                <div class="clear"></div>
            </div>
		</div>
	</div>
	<p class="submit" style="clear:left">
        <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'authorizedstore' ) ?>" name="authstore_save" />
	</p>
</form>