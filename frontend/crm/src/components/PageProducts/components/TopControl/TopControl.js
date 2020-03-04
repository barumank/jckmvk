import React from 'react'
import style from './TopControl.module.css'
import {Button, Icon} from 'semantic-ui-react'
import {Link} from "react-router-dom"

const TopControl = (props) => {
    const {showNewCategoryModal, onBack} = props;

    const getBackButton = () => {
        if (onBack) {
            return (<Button className={style.back} onClick={onBack}>Назад</Button>);
        }
        return (<Button className={style.back} disabled>Назад</Button>);
    };
    return (
        <div className={style.buttonContainer}>
            <div className={style.buttonGroup}>
                <Button className={style.addGroup} onClick={() => showNewCategoryModal()}><Icon
                    name='plus circle'/> Создать группу</Button>
                {getBackButton()}
            </div>
            <div className={style.buttonGroup}>
                <Button as={Link} to='/product' className={style.addProduct}><Icon name='plus circle'/> Добавить
                    товар</Button>
                <Button as={Link} to='/associate-analog' className={style.attachAnalog}><Icon name='attach'/> Привязать
                    аналог</Button>
            </div>
        </div>
    );
};

export default TopControl