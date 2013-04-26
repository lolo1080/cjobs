{strip}
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>

			{section name=i loop=$CurFormButtons}
			<td>
				<table class="buttonborder{$CurFormButtons[i].btn_classnum}1" cellPadding="0" cellSpacing="0">
				<tr>
					<td>
						<table class="buttonborder{$CurFormButtons[i].btn_classnum}2" cellPadding="0" cellSpacing="0">
								<tr>
									<td>
										<table class="buttonborder{$CurFormButtons[i].btn_classnum}3" cellPadding="0" cellSpacing="0">
											<tr>
												<td>
														<table class="buttonborder{$CurFormButtons[i].btn_classnum}4" cellPadding="0" cellSpacing="0">
															<tr>
																<td>
																	<input class="button" type="{$CurFormButtons[i].btype}" name="{$CurFormButtons[i].bname}" value="{$CurFormButtons[i].bvalue}" {$CurFormButtons[i].bscript} />
																</td>
															</tr>
														</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td width="{$btnspace}">&nbsp;</td>
			{/section}

		</tr>
		</table>
{/strip}