import style from './ObjectDirectory.module.css'
import React from 'react'
import {Segment, Header, Icon, Divider, Image, Button} from 'semantic-ui-react'


const ObjectDirectory = (props)=>{

    return(
        <Segment className={style.container}>
            <Header as='h4' className={style.directoryName}>
                <Icon name='folder open' className={style.directoryIcon} /> Overlayable Section
            </Header>
            <Divider className={style.dividerTop} />
            <Image.Group className={style.imageGroup} >
                <Image src='/img/1.jpg' className={style.image} />
                <Image src='/img/1.jpg' className={style.image} />
                <Image src='/img/111.jpg' className={style.image} />
            </Image.Group>
            <Divider className={style.dividerBottom} />
            <div className={style.control}>
                <Button><Icon name='pencil alternate'/></Button>
                <Button><Icon name='trash alternate'/></Button>
            </div>
        </Segment>
    );
};

export default ObjectDirectory;