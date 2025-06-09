<!--
  - SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<div id="overleaf_prefs" class="section">
		<h2>
			{{ t('integration_overleaf', 'Overleaf integration') }}
		</h2>
		<div id="overleaf-content">
			<NcTextField
				id="overleaf-server"
				class="input"
				:value.sync="overleaf_server"
				:label="t('integration_overleaf', 'Overleaf Server URL')"
				:placeholder="t('integration_overleaf', 'https://overleaf.com')"
				@update:value="onInput()"/>
		</div>
	</div>
</template>

<script>
import NcTextField from '@nextcloud/vue/dist/Components/NcTextField.js'
import {loadState} from '@nextcloud/initial-state'
import debounce from 'debounce'
import {generateOcsUrl} from "@nextcloud/router";
import axios from "@nextcloud/axios";


export default {
	name: 'AdminSettings',

	components: {
		NcTextField,
	},

	data() {
		return {
			overleaf_server: loadState('integration_overleaf', 'server-config'),
		}
	},

	methods: {
		onInput: debounce(async function () {
			const values = {
				overleafServer: this.overleaf_server,
			}
			await this.saveOptions(values, false)
		}, 2000),
		async saveOptions(values) {
			const url = generateOcsUrl('/apps/integration_overleaf/api/admin-config')
			await axios.put(url, values)

		}
	},
}
</script>

<style scoped lang="scss">

</style>
