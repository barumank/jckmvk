import style from './Object.module.css'
import React from 'react'
import {Button, Divider, Header, Icon, Image, Segment, Item,Grid} from "semantic-ui-react";

const Object = (props)=>{

    return(
        <Segment className={style.container}>
            <Header as='h4' className={style.objectName}>
                <Icon name='home' className={style.objectIcon} /> Overlayable Section
            </Header>
            <Divider className={style.dividerTop} />
            <Item className={style.item}>
                <Image className={style.image} src='/img/1.jpg'/>
                <Item.Content className={style.itemContent}>
                    <Grid columns={2}>
                        <Grid.Row>
                            <Grid.Column>
                                Дата
                            </Grid.Column>
                            <Grid.Column>
                                09.10.19
                            </Grid.Column>
                        </Grid.Row>
                        <Grid.Row>
                            <Grid.Column>
                                Адрес
                            </Grid.Column>
                            <Grid.Column>
                                Самарская область...
                            </Grid.Column>
                        </Grid.Row>
                        <Grid.Row>
                            <Grid.Column>
                                Площадь
                            </Grid.Column>
                            <Grid.Column>
                                150 м²
                            </Grid.Column>
                        </Grid.Row>
                        <Grid.Row>
                            <Grid.Column>
                                Этажи
                            </Grid.Column>
                            <Grid.Column>
                                2
                            </Grid.Column>
                        </Grid.Row>
                        <Grid.Row>
                            <Grid.Column>
                                Радиаторы
                            </Grid.Column>
                            <Grid.Column>
                               10
                            </Grid.Column>
                        </Grid.Row><Grid.Row>
                            <Grid.Column>
                                Тёплый пол
                            </Grid.Column>
                            <Grid.Column>
                                да
                            </Grid.Column>
                        </Grid.Row><Grid.Row>
                            <Grid.Column>
                                Сумма
                            </Grid.Column>
                            <Grid.Column className={style.green}>
                                1 234 567
                            </Grid.Column>
                        </Grid.Row>
                    </Grid>
                </Item.Content>
            </Item>
            <Divider className={style.dividerBottom} />
            <div className={style.control}>
                <Button.Group className={style.statusGroup}>
                    <Button>Предварительный</Button>
                    <Button>В работе</Button>
                    <Button positive>Закрытый</Button>
                </Button.Group>
                <div className={style.controlContainer}>
                <Button className={style.smile}><Icon name='copy'/></Button>
                <Button className={style.smile}><Icon name='trash alternate'/></Button>
                </div>
            </div>
        </Segment>
    );
};

export default Object;