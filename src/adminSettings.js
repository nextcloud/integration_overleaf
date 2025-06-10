/**
 * SPDX-FileCopyrightText: 2022 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import Vue from 'vue'
// eslint-disable-next-line import/no-unresolved,n/no-missing-import
import AdminSettings from './components/AdminSettings.vue'
Vue.mixin({ methods: { t, n } })

const View = Vue.extend(AdminSettings)
new View().$mount('#overleaf_prefs')
