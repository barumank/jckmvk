import React, {Fragment, useState, useEffect} from 'react'
import style from './TreeView.module.css'
import {Button, Form, Modal, Segment, List, Breadcrumb, Icon, Header} from "semantic-ui-react";
import Popup from "../Popup/Popup";


const NodeView = (props) => {
    const {
        node,
        selectId,
        setSelectId,
        setSelectParentId,
        childrenMap,
        blockedNode
    } = props;

    const isDir = childrenMap.has(node.id);

    const hasBlocked = blockedNode === node.id;

    const getItemClass = () => {
        if (hasBlocked) {
            return `${style.viewItem} ${style.blocked}`;
        }
        return selectId === node.id ? `${style.viewItem} ${style.select}` : style.viewItem
    };

    return (
        <List.Item
            className={getItemClass()}
            onClick={(e) => {
                e.stopPropagation();
                if (hasBlocked) {
                    return;
                }
                setSelectId(node.id);
            }}
            onDoubleClick={(e)=>{
                e.stopPropagation();
                if (hasBlocked || !isDir) {
                    return;
                }
                setSelectParentId(node.id);
                setSelectId(node.id);
            }}
        >
            <List.Icon name='folder'/>
            <List.Content>
                <List.Header>{node.name}</List.Header>
            </List.Content>
            {isDir && !hasBlocked && (
                <Popup
                    trigger={
                        <div className={style.buttonOpenDir}
                             onClick={(e) => {
                                 e.stopPropagation();
                                 setSelectParentId(node.id);
                                 setSelectId(node.id);
                             }}>
                            <Icon name='angle right'/>
                        </div>
                    }
                    content='Открыть папку'
                />
            )}
        </List.Item>
    )
};


const ViewBreadcrumb = (props) => {
    const {breadcrumb, setSelectParentId} = props;
    const elements = [];

    for (let i = 0; i < breadcrumb.length; i++) {
        elements.push(<Breadcrumb.Divider icon='folder open' key={`d_${breadcrumb[i].id}`}/>);
        if (breadcrumb.length - 1 === i) {
            elements.push(<Breadcrumb.Section active key={breadcrumb[i].id}>{breadcrumb[i].name}</Breadcrumb.Section>)
        } else {
            elements.push(<Breadcrumb.Section
                link
                key={breadcrumb[i].id}
                onClick={() => setSelectParentId(breadcrumb[i].id)}>
                {breadcrumb[i].name}
            </Breadcrumb.Section>);
            elements.push(<Breadcrumb.Divider icon='angle right' key={`e_${breadcrumb[i].id}`}/>);
        }
    }

    return (
        <Segment loading={false}>
            <Breadcrumb className={style.breadcrumb}>
                {elements}
            </Breadcrumb>
        </Segment>
    );
};

const getPath = (list) => list.map((item) => item.name).join('/');

const getData = (nodeList, selectId, selectParentId = 0, rootNodeName = '') => {
    const childrenMap = new Map();
    const parentMap = new Map();
    const itemMap = new Map();
    for (let item of nodeList) {
        parentMap.set(item.id, item.parentId);
        itemMap.set(item.id, item);
        if (!childrenMap.has(item.parentId)) {
            childrenMap.set(item.parentId, []);
        }
        let children = childrenMap.get(item.parentId);
        children.push(item);
    }


    const tree = (item, list) => {
        if (parentMap.has(item.id)) {
            let parentId = parentMap.get(item.id);
            list.push(item);
            if (parentId && itemMap.has(parentId)) {
                let parent = itemMap.get(parentId);
                tree(parent, list);
            }
        }
    };

    let parentHasEmpty = selectParentId === null;
    if (!selectParentId) {
        if (itemMap.has(selectId)) {
            selectParentId = itemMap.get(selectId).parentId;
        }

    }
    let breadcrumb = [];
    if (selectParentId && itemMap.has(selectParentId)) {
        let item = itemMap.get(selectParentId);
        tree(item, breadcrumb)
    }

    breadcrumb.push({id: 0, parentId: 0, name: rootNodeName});
    breadcrumb = breadcrumb.reverse();

    let path = [...breadcrumb];
    if (!parentHasEmpty && itemMap.has(selectId)) {
        path.push(itemMap.get(selectId));
    }

    return {
        childrenMap,
        parentMap,
        itemMap,
        breadcrumb,
        path,
        selectParentId
    }

};

const ModalView = (props) => {

    const {
        onClose, isOpen, nodeList,
        title,
        rootNodeName,
        selectId,
        setSelectId,
        selectParentId,
        setSelectParentId,
        setSelectPath,
        blockedNode,
        onSelectDir,
        selectButtonName
    } = props;

    if (!Array.isArray(nodeList)) {
        return (<Fragment/>);
    }

    const {childrenMap, breadcrumb, path} = getData(nodeList, selectId, selectParentId, rootNodeName);

    let list = nodeList.filter((item) => {
        if (selectParentId === item.parentId) {
            return true;
        } else if (selectParentId === 0
            && (item.parentId === 0 || item.parentId === null || item.parentId === '0')) {
            return true;
        }
    });

    return (
        <Modal size='small' open={isOpen} onClose={onClose} closeIcon>
            <Modal.Header>{title}</Modal.Header>
            <Modal.Content onClick={() => {
                setSelectId(0);
            }}>
                <ViewBreadcrumb
                    breadcrumb={breadcrumb}
                    setSelectParentId={setSelectParentId}
                />
                <Segment loading={false}>
                    <List>
                        {list.sort().map((item) => (
                            <NodeView
                                key={item.id}
                                node={item}
                                selectId={selectId}
                                setSelectId={setSelectId}
                                setSelectParentId={setSelectParentId}
                                childrenMap={childrenMap}
                                blockedNode={blockedNode}
                            />))}
                    </List>
                </Segment>
            </Modal.Content>
            <Modal.Actions>
                <Button negative onClick={onClose}>Отмена</Button>
                <Button positive onClick={(e) => {
                    onSelectDir({
                        id: selectId,
                        parentId: selectParentId
                    });
                    setSelectPath(getPath(path));
                    onClose();
                }}>{selectButtonName}</Button>
            </Modal.Actions>
        </Modal>
    );
};

const TreeView = (props) => {
    const {nodeList, selectedId, selectedParentId, title, label, selectButtonName} = props;
    let {rootNodeName, onSelectDir, blockedNode} = props;
    const [isOpen, setIsOpen] = useState(false);
    const [selectId, setSelectId] = useState(0);
    const [selectParentId, setSelectParentId] = useState(0);
    if (!rootNodeName) {
        rootNodeName = 'Диск'
    }
    if (!blockedNode) {
        blockedNode = selectedId;
    }
    if (typeof onSelectDir !== 'function') {
        onSelectDir = () => {
        };
    }
    const [selectPath, setSelectPath] = useState(rootNodeName);

    useEffect(() => {
        if (selectedId) {
            const {path, selectParentId} = getData(nodeList, selectedId, null, rootNodeName);
            setSelectPath(getPath(path));
            setSelectId(selectedId);
            setSelectParentId(selectParentId);
        } else if (selectedParentId) {
            const {path, selectParentId} = getData(nodeList, 0, selectedParentId, rootNodeName);
            setSelectPath(getPath(path));
            setSelectParentId(selectParentId);
        }
        onSelectDir({
            id: selectId,
            parentId: selectParentId
        });

    }, [selectedId]);

    return (
        <>
            <Form.Input
                readOnly
                label={label}
                value={selectPath}
                action={<Button onClick={(e) => {
                    e.preventDefault();
                    setIsOpen(true);
                }}>обзор</Button>}
            />
            <ModalView
                isOpen={isOpen}
                onClose={() => {
                    setIsOpen(false);
                }}
                title={title}
                nodeList={nodeList}
                selectId={selectId}
                setSelectId={setSelectId}
                selectParentId={selectParentId}
                setSelectParentId={setSelectParentId}
                onSelectDir={onSelectDir}
                setSelectPath={setSelectPath}
                rootNodeName={rootNodeName}
                blockedNode={blockedNode}
                selectButtonName={selectButtonName}
            />
        </>
    );
};

export default TreeView;