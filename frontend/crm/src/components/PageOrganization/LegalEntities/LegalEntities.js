import React from 'react'
import style from './LegalEntities.module.css'
import {Button, Grid, Header, Menu} from "semantic-ui-react";

const LegalEntities = (props) => {
    return (
        <>
            <Header as='h5'>Мои юрлица</Header>
            <Menu text vertical className={style.container}>
                <Menu.Item
                    href='#'
                    name='closest'
                />
                <Menu.Item
                    href='#'
                    name='mostCommentsttttttttttttttttttttttttttttttttttt'
                />
                <Menu.Item
                    href='#'
                    name='mostPopular'
                />
            </Menu>
            <Button positive>Добавить</Button>
        </>
    );
};

export default LegalEntities