import {BitrixVue} from 'ui.vue3';
export class Example
{
	constructor(rootNode)
	{
		this.rootNode = document.querySelector(rootNode);
	}
	run()
	{
		BitrixVue.createApp({
			template: "Example application"
		}).mount(this.rootNode);
	}
}