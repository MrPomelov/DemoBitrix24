<?php

namespace Models\Lists;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Models\AbstractIblockPropertyValuesTable;

class ProceduresPropertyValuesTable extends AbstractIblockPropertyValuesTable
{
    const IBLOCK_ID = 17;

    public static function getMap(): array
    {
        $map = [
            'ID' => (new IntegerField('IBLOCK_ELEMENT_ID',
					[]
				))->configureTitle('IBLOCK_ELEMENT_ID')
						->configurePrimary(true)
						->configureAutocomplete(true)
			,
            'COST' => (new StringField('COST',
                    []
                ))->configureTitle('COST')
            ,
            // 'PROCEDURES_DATA' => new ReferenceField(
            //     'PROCEDURES', 
            //     ProceduresPropertyValuesTable::class, 
            //     ['=this.PROCEDURES' => 'ref.IBLOCK_ELEMENT_ID']
            // )
        ];

        return parent::getMap() + $map; // TODO: Change the autogenerated stub

    }
}