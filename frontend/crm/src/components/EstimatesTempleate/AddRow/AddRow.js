import React from 'react'
import style from './AddRow.module.css'
import {Button, Icon, Popup} from "semantic-ui-react";

const AddRow = (props) => {

    return (
        <Popup  hoverable basic {...props} className={style.contentIcon} style={{top:'12px'}} on={['hover']} position='top left'>
            <Icon name='plus circle' />
            <Button className={style.contentButton}>Добавить позицию</Button>
        </Popup>
    );
};

export default AddRow;