import React from 'react'
import {Header, Segment, Table, Button, Icon} from "semantic-ui-react";
import style from "./FileGroup.module.css";
import {Scrollbars} from "react-custom-scrollbars";


const FileGroup = (props) => {

    const {
        name,
        statusClass,
    } = props;

    const renderThumb = ({style, ...props}) => {
        style = {...style, width: '14px', borderRadius: '3px'};
        return (
            <div
                className='groupScroll'
                style={{...style}}
                {...props}/>
        );
    };
    const renderTrack = ({style,...props}) => {
        style = {
            ...style,
            width: '16px',
            bottom: 0,
            top: 0,
            right: '0',
            border: '1px solid #dededf',
            backgroundColor: '#ffffff'
        };
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };
    const renderView = ({style, ...props}) => {
        style = {...style, paddingRight: '16px'};
        return (
            <div
                style={{...style}}
                {...props}/>
        );
    };

    return (
        <Segment className={`${style.container} ${statusClass}`}>
            <Header as='h4' className={style.header}>{name}</Header>
            <Scrollbars
                        renderThumbVertical={renderThumb}
                        renderTrackVertical={renderTrack}
                        renderView={renderView}
                        renderTrackHorizontal={() => (<div style={{display: 'none'}}/>)}
                        renderThumbHorizontal={() => (<div/>)}
                        autoHeight
                        autoHeightMin={0}
                        autoHeightMax={161}
            >
                <Table selectable celled>
                    <Table.Body>
                        <Table.Row>
                            <Table.Cell><Icon name='file pdf'/> тест тест</Table.Cell>
                            <Table.Cell className={style.downloadFile}><Icon name='download'/></Table.Cell>
                        </Table.Row>
                        <Table.Row>
                            <Table.Cell><Icon name='file pdf'/> тест тест</Table.Cell>
                            <Table.Cell className={style.downloadFile}><Icon name='download'/></Table.Cell>
                        </Table.Row>
                        <Table.Row>
                            <Table.Cell><Icon name='file pdf'/> тест тест</Table.Cell>
                            <Table.Cell className={style.downloadFile}><Icon name='download'/></Table.Cell>
                        </Table.Row>
                        <Table.Row>
                            <Table.Cell><Icon name='file pdf'/> тест тест</Table.Cell>
                            <Table.Cell className={style.downloadFile}><Icon name='download'/></Table.Cell>
                        </Table.Row>
                        <Table.Row>
                            <Table.Cell><Icon name='file pdf'/> тест тест</Table.Cell>
                            <Table.Cell className={style.downloadFile}><Icon name='download'/></Table.Cell>
                        </Table.Row>
                    </Table.Body>
                </Table>
            </Scrollbars>
            <Button className='upload'>Загрузить документ</Button>
        </Segment>
    );
};

export default FileGroup;
