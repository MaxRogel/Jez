

<div class=loci-nav>
			<p></p>	
			
			<table>
			<tr>
				<th colspan="4">Loci Navigation</th>
			</tr>
			<tr>
				<td><button type="button" class="Btn" id="btnFirst" style="width:100%">First</button></td>
				<td><button type="button" class="Btn" id="btnPrev" style="width:100%">Prev</button></td>
				<td><button type="button" class="Btn" id="btnNext" style="width:100%">Next</button></td>
				<td><button type="button" class="Btn" id="btnLast" style="width:100%">Last</button></td>
			</tr>
			
			</table>
			
			<table>
			<tr>
				<td>
					<select name="Area" id="area-list" class="Cmbx" onChange="getLocusList(this.value);">
					<option value="">Area</option>
					<?php
					foreach($areas as $a) {
						?>
						<option value="<?php echo $a["Area_ID"]; ?>"><?php echo $a["YYYY"]. '.'. $a["AreaName"]; ?></option>
						<?php
					}
					?>
					</select>
				</td>
				<td>
					<select name="state" id="loci-list" class="Cmbx">
						<option value="">Locus</option>
					</select>
				</td>
				<td><button type="button" class="Btn" id="btnGo" style="width:100%">GO!</button></td>
			</tr>
			
			</table>
		</div>

		