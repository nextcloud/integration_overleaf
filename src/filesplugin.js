import {
	registerFileAction, Permission, FileAction, FileType,
} from '@nextcloud/files'
import {generateOcsUrl} from '@nextcloud/router'
import axios from '@nextcloud/axios'

const sendAction = new FileAction({
	id: 'overleafOpen',
	displayName: (nodes) => {
		return nodes.length > 1
			? t('integration_overleaf', 'Open files in Overleaf')
			: t('integration_overleaf', 'Open file in Overleaf')
	},
	enabled(nodes, view) {
		return nodes.length > 0
			&& !nodes.some(({ permissions }) => (permissions & Permission.READ) === 0)
			&& nodes.every(({ type }) => type === FileType.File)
			&& nodes.every(({ mime }) => mime === 'application/x-tex')
	},
	iconSvgInline: () => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">',
	async exec(node) {
		await openInOverleaf([node])
		return null
	},
	async execBatch(nodes) {
		await openInOverleaf(nodes)
		return nodes.map(_ => null)
	},
})
registerFileAction(sendAction)

async function openInOverleaf(node) {
	const url = generateOcsUrl('/apps/integration_overleaf/api/overleaf')
	const req = {
		fileIds: node.map((e) => e.fileid),
	}
	const response = await axios.post(url, req)
	window.location.replace(response.data.ocs.data)
}
