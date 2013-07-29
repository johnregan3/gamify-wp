
	function addActionRows() {
		var actionId = Math.floor(Math.random()*9999);
		var table=document.getElementById("gamwp-action-table");
		var row=table.insertRow(1);
		var cell1=row.insertCell(0);
		var cell2=row.insertCell(1);
		var cell3=row.insertCell(2);
		var cell4=row.insertCell(3);
		var cell5=row.insertCell(4);
		var cell6=row.insertCell(5);
		cell1.innerHTML="<td><input type='checkbox' id='gamwp_action_settings[" + actionId + "][delete]' name='gamwp_action_settings[" + actionId + "][delete]' value='checked' /></td>";
		cell2.innerHTML="<td><input type='text' id='gamwp_action_settings[" + actionId + "][activity_title]' name='gamwp_action_settings[" + actionId + "][activity_title]' value='' title='' placeholder='Action Title' /></td>";
		cell3.innerHTML="<td><input type='text' id='gamwp_action_settings[" + actionId + "][action_hook]' name='gamwp_action_settings[" + actionId + "][action_hook]' value='' title='' placeholder='Action Hook' /></td>";
		cell4.innerHTML="<td><input type='text' id='gamwp_action_settings[" + actionId + "][activity_points]' name='gamwp_action_settings[" + actionId + "][activity_points]' value='' title='' placeholder='Points' /></td>";
		cell5.innerHTML="<td><input type='checkbox' id='gamwp_action_settings[" + actionId + "][daily_limit]' name='gamwp_action_settings[" + actionId + "][daily_limit]' value='checked' /></td>";
		cell6.innerHTML="<td><input type='checkbox' id='gamwp_action_settings[" + actionId + "][once]' name='gamwp_action_settings[" + actionId + "][once]' value='checked' /></td>";
	}

	function addRewardRows() {
		var actionId = Math.floor(Math.random()*9999);
		var table=document.getElementById("gamwp-rew-table");
		var row=table.insertRow(1);
		var cell1=row.insertCell(0);
		var cell2=row.insertCell(1);
		var cell3=row.insertCell(2);
		var cell4=row.insertCell(3);
		cell1.innerHTML="<td><input type='checkbox' id='gamwp_rew_settings[" + actionId + "][once]' name='gamwp_rew_settings[" + actionId + "][once]' value='checked' /></td>";
		cell2.innerHTML="<td><input type='text' id='gamwp_rew_settings[" + actionId + "][reward_title]' name='gamwp_rew_settings[" + actionId + "][reward_title]' value='' title='' placeholder='Reward Title' /></td>";
		cell3.innerHTML="<td><select id='gamwp_rew_settings[" + actionId + "][reward_type]' name='gamwp_rew_settings[" + actionId + "][reward_type]'><option>Level</option></select></td>";
		cell4.innerHTML="<td><input type='text' id='gamwp_rew_settings[" + actionId + "][reward_points]' name='gamwp_rew_settings[" + actionId + "][reward_points]' value='' title='' placeholder='Points' /></td>";
	}
