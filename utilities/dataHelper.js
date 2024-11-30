export const FormattedDate = (date) => {
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var year = date.getFullYear();

    month = month < 10 ? '0' + month : month;
    day = day < 10 ? '0' + day : day;

    return `${month}-${day}-${year}`;
}