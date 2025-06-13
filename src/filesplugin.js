import {
	registerFileAction, Permission, FileAction, FileType,
} from '@nextcloud/files'
import { generateOcsUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
// eslint-disable-next-line import/no-unresolved
import OverleafLogoSVG from '../img/app-no-color.svg?raw'

const sendAction = new FileAction({
	id: 'overleafOpen',
	displayName: (nodes) => {
		return t('integration_overleaf', 'Open file in Overleaf')
	},
	enabled(nodes, view) {
		return nodes.length === 1
			&& !nodes.some(({ permissions }) => (permissions & Permission.READ) === 0)
			&& nodes.every(({ type }) => type === FileType.File)
			&& nodes.every(({ mime }) => mime === 'application/x-tex' || mime === 'application/zip')
	},
	iconSvgInline: () => OverleafLogoSVG,
	async exec(node) {
		await openInOverleaf(node)
		return null
	},
	async execBatch(nodes) {
		await openInOverleaf(nodes[0])
		return nodes.map(_ => null)
	},
})
registerFileAction(sendAction)

async function openInOverleaf(node) {
	const url = generateOcsUrl('/apps/integration_overleaf/api/overleaf')
	const req = {
		fileId: node.fileid,
	}
	const response = await axios.post(url, req)
	window.open(response.data.ocs.data, '_blank')
}
