import {createAppConfig} from "@nextcloud/vite-config";
import {join, resolve} from "path";

export default createAppConfig(
	{
		filesplugin: resolve(join("src", "filesplugin.js")),
		adminSettings: resolve(join("src", "adminSettings.js")),
	},
	{
		createEmptyCSSEntryPoints: true,
		extractLicenseInformation: true,
		thirdPartyLicense: false,
	}
);
