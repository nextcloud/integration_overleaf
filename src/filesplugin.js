import {
	registerFileAction, Permission, FileAction, FileType,
} from '@nextcloud/files'
import {generateOcsUrl} from '@nextcloud/router'
import axios from '@nextcloud/axios'

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
	iconSvgInline: () => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">',
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
	window.location.replace(response.data.ocs.data)
}
