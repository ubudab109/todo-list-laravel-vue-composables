/**
 * The function `defaultResponseError` returns an error message based on the given status code and
 * message.
 * @param {Number} statusCode - The `statusCode` parameter represents the HTTP status code of the response. It
 * is used to determine the type of error response.
 * @param {String} message - The `message` parameter is a string that represents the error message.
 * @returns {String} The function `defaultResponseError` returns the variable `messageError`.
 */
const defaultResponseError = (statusCode, message) => {
    let messageError;
    if (statusCode === 422) {
        messageError = message;
    } else {
        messageError = [message];
    }
    return messageError;
};

/**
 * The function `badgePriority` returns a CSS class based on the priority value passed as an argument.
 * @param {Number} priority - The `priority` parameter is a number that represents the priority level of a
 * badge. It can have values from 0 to 3.
 * @returns {String} The function `badgePriority` returns a CSS class name based on the input `priority`.
 */
const badgePriority = (priority) => {
    const data = {
        0: 'inline-block px-2 py-1 leading-none bg-green-200 text-green-800 rounded-full font-semibold uppercase tracking-wide text-xs',
        1: 'inline-block px-2 py-1 leading-none bg-blue-200 text-blue-800 rounded-full font-semibold uppercase tracking-wide text-xs',
        2: 'inline-block px-2 py-1 leading-none bg-orange-200 text-orange-800 rounded-full font-semibold uppercase tracking-wide text-xs',
        3: 'inline-block px-2 py-1 leading-none bg-red-200 text-red-800 rounded-full font-semibold uppercase tracking-wide text-xs',
    }
    return data[priority]
};

/**
 * The function `textPriority` takes a priority value and returns the corresponding text
 * representation.
 * @param {Number} priority - The `priority` parameter is a number that represents the priority level of a task.
 * @returns {String} The function `textPriority` returns the corresponding text value for a given priority
 * level.
 */
const textPriority = (priority) => {
    const data = {
        0: 'LOW',
        1: 'NORMAL',
        2: 'HIGH',
        3: 'URGENT',
    };
    return data[priority];
};

/**
 * The function formats a string date into a day/month/year format.
 * @param {String} stringDate - A string representing a date in a specific format (e.g. "2021-10-15T10:30:00Z").
 * @returns {String}
 */
const formatTimestamp = (stringDate) => {
    const date = new Date(stringDate);
    const month = [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
    ];
    return date.getDate() + ' ' + month[date.getMonth()] + ' ' + date.getFullYear();
};

/**
 * Priority fiels data
 * 
 * @return {Array}
 */
const priorityFields = () => {
    return [
        {
            value: '0',
            text: 'LOW',
        },
        {
            value: '1',
            text: 'NORMAL',
        },
        {
            value: '2',
            text: 'HIGH',
        },
        {
            value: '3',
            text: 'URGENT',
        },
    ];
};

export default {
    defaultResponseError,
    badgePriority,
    textPriority,
    formatTimestamp,
    priorityFields,
};
