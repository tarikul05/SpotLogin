<table style="background-color:#f9f9f9; border-radius:10px; padding:20px; margin:0 auto; text-align:center; width: 100%; max-width:550px; margin: 0 auto; font-family:Arial, Helvetica, sans-serif;">
	<tbody>
		<tr>
			<td>
			<table style="margin:0 auto; text-align:center; max-width:490px">
				<tbody>
                    <tr>
                        <td style="text-align:center; max-width:450px; margin: 0 auto;">
                        <p><a href="[~~HOSTNAME~~]"><img alt="SPORTLOGIN" src="http://sportlogin.ch/img/banner-sport-login.jpg" style="max-width:450px; border-radius:10px; margin: 0 auto;"/></a></p>
                        </td>
                    </tr>
					<tr>
						<td style="text-align:center">&nbsp;</td>
					</tr>
					<tr>
						<td style="text-align:center"><strong>SportLogin Administrator,</strong></td>
					</tr>
					<tr>
					</tr>
					<tr>
						<td style="text-align:center">
                            Rapport du {{ $period_start }} au {{ $period_end }}
                            <hr>
                            <h5>Utilisateurs</h5>
                            <p>This week: {{ $user_week }}</p>
                            <p>This month: {{ $user_month }}</p>
                            <hr>
                        
                            <h5>Abonnements Stripe</h5>
                            <p>Total: {{ $subscriptions_count }}</p>
                            <p>This week: {{ $subscriptions_this_week }}</p>
                            <hr>
                        
                            <h5>Nouvelles lessons et events</h5>
                            <p>This week: {{ $weekCount }}</p>
                            <p>This month: {{ $monthCount }}</p>
                            <hr>
                        
                            <h5>Système de facturation</h5>
                            <p>This week: {{ $invoice_week }}</p>
                            <p>This month: {{ $invoice_month }}</p>
                            <hr>
                        </td>
					</tr>
					<tr>
						<td>
						<p>&nbsp;</p>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; margin:0 auto; max-width:300px; font-size:12px;"><em>Automatic report for Admins.</em>
						</td>
					</tr>
					<tr>
						<td>
						<table style="text-align:center; padding-top:25px; margin:0 auto; max-width:350px">
							<tbody>
								<tr>
									<td style="vertical-align:top; width:28px"><a href="https://www.instagram.com/sportlogin/?hl=fr" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M17.833 6.595v1.476c0 .237-.193.429-.435.429h-1.465c-.238 0-.434-.192-.434-.429v-1.476c0-.237.195-.428.434-.428h1.465c.242 0 .435.191.435.428zm-5.833 7.498c1.121 0 2.028-.908 2.028-2.029s-.907-2.029-2.028-2.029-2.028.908-2.028 2.029.907 2.029 2.028 2.029zm12-2.093c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12zm-5-1.75h-3.953c.316.533.508 1.149.508 1.813 0 1.968-1.596 3.564-3.563 3.564-1.969 0-3.564-1.596-3.564-3.564 0-.665.191-1.281.509-1.813h-3.937v5.996c0 1.521 1.27 2.754 2.791 2.754h8.454c1.521 0 2.755-1.233 2.755-2.754v-5.996zm-7.009 4.559c1.515 0 2.745-1.232 2.745-2.746 0-.822-.364-1.56-.937-2.063-.202-.177-.429-.324-.677-.437-.346-.157-.729-.245-1.132-.245-.405 0-.788.088-1.133.245-.246.112-.474.26-.675.437-.574.503-.938 1.242-.938 2.063.001 1.514 1.234 2.746 2.747 2.746zm7.009-7.055c0-1.521-1.234-2.754-2.755-2.754h-7.162v2.917h-.583v-2.917h-.583v2.917h-.584v-2.872c-.202.033-.397.083-.583.157v2.715h-.583v-2.393c-.702.5-1.167 1.31-1.167 2.23v1.913h4.359c.681-.748 1.633-1.167 2.632-1.167 1.004 0 1.954.422 2.631 1.167h4.378v-1.913z"/></svg>
                                    </a></td>
									<td style="vertical-align:top; width:28px"><a href="https://www.youtube.com/channel/UCr3cA2djrLUHTBH_vM7-TMQ" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M10.918 13.933h.706v3.795h-.706v-.419c-.13.154-.266.272-.405.353-.381.218-.902.213-.902-.557v-3.172h.705v2.909c0 .153.037.256.188.256.138 0 .329-.176.415-.284v-2.881zm.642-4.181c.2 0 .311-.16.311-.377v-1.854c0-.223-.098-.38-.324-.38-.208 0-.309.161-.309.38v1.854c-.001.21.117.377.322.377zm-1.941 2.831h-2.439v.747h.823v4.398h.795v-4.398h.821v-.747zm4.721 2.253v2.105c0 .47-.176.834-.645.834-.259 0-.474-.094-.671-.34v.292h-.712v-5.145h.712v1.656c.16-.194.375-.354.628-.354.517.001.688.437.688.952zm-.727.043c0-.128-.024-.225-.075-.292-.086-.113-.244-.125-.367-.062l-.146.116v2.365l.167.134c.115.058.283.062.361-.039.04-.054.061-.141.061-.262v-1.96zm10.387-2.879c0 6.627-5.373 12-12 12s-12-5.373-12-12 5.373-12 12-12 12 5.373 12 12zm-10.746-2.251c0 .394.12.712.519.712.224 0 .534-.117.855-.498v.44h.741v-3.986h-.741v3.025c-.09.113-.291.299-.436.299-.159 0-.197-.108-.197-.269v-3.055h-.741v3.332zm-2.779-2.294v1.954c0 .703.367 1.068 1.085 1.068.597 0 1.065-.399 1.065-1.068v-1.954c0-.624-.465-1.071-1.065-1.071-.652 0-1.085.432-1.085 1.071zm-2.761-2.455l.993 3.211v2.191h.835v-2.191l.971-3.211h-.848l-.535 2.16-.575-2.16h-.841zm10.119 10.208c-.013-2.605-.204-3.602-1.848-3.714-1.518-.104-6.455-.103-7.971 0-1.642.112-1.835 1.104-1.848 3.714.013 2.606.204 3.602 1.848 3.715 1.516.103 6.453.103 7.971 0 1.643-.113 1.835-1.104 1.848-3.715zm-.885-.255v.966h-1.35v.716c0 .285.024.531.308.531.298 0 .315-.2.315-.531v-.264h.727v.285c0 .731-.313 1.174-1.057 1.174-.676 0-1.019-.491-1.019-1.174v-1.704c0-.659.435-1.116 1.071-1.116.678.001 1.005.431 1.005 1.117zm-.726-.007c0-.256-.054-.445-.309-.445-.261 0-.314.184-.314.445v.385h.623v-.385z"/></svg>
                                    </a></td>
									<td style="vertical-align:top; width:28px"><a href="https://www.facebook.com/sportlogicel/" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/></svg></a></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td style="text-align:center; width:150px; display:none;"><small><em>You&rsquo;ve received this email since you are Admin of SportLogin's Team.</em></small></td>
					</tr>
					<tr>
						<td>
					
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>