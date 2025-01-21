import {Type} from 'main.core';

export class ColorMyTask
{
	constructor(options = {name: 'ColorMyTask'})
	{
		this.name = options.name;
	}

	setName(name)
	{
		if (Type.isString(name))
		{
			this.name = name;
		}
	}

	getName()
	{
		return this.name;
	}
}
