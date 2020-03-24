import Dropdown from '../componets/Dropdown/Dropdown';
import { connect } from 'react-redux';
import {getIsHide, getOptions, getProductId, getValue} from "./selectors"
import {onChange} from "./reducer";

export default connect(
    state => ({
        isHide: getIsHide(state),
        options: getOptions(state),
        productId: getProductId(state),
        value: getValue(state),
    }),
    dispatch => ({
        onChange: (event, data) => {
            dispatch(onChange(event, data));
        }
    }),
)(Dropdown);

