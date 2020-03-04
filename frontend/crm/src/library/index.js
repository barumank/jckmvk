export const checkChangesCreator = (compareObjects) => (objValue, othValue) => {
    if (Array.isArray(objValue) && Array.isArray(othValue)) {
        if (objValue !== othValue) {
            return false;
        }
        if (objValue.length !== othValue.length) {
            return false;
        }
        if (objValue.length === 0 && othValue.length === 0) {
            return true;
        }
    } else if (objValue === null && othValue === null) {
        return true;
    } else if ((objValue === null && othValue !== null)
        || (objValue !== null && othValue === null)) {
        return false;
    } else {
        return compareObjects(objValue, othValue);
    }
};