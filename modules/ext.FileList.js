window.fileListSubmit = function(prefix) {
    form = document.filelistform;
    filename = getNameFromPath(form.wpUploadFile.value);
    if( filename == "" ) {
        fileListError(mw.msg('fl-empty-file'));
        return false;
    }
    form.wpDestFile.value = prefix + filename;
    return true;
}

window.fileListError = function(message) {
    document.getElementById("filelist_error").innerHTML = message;
}

window.getNameFromPath = function(strFilepath) {
    var objRE = new RegExp(/([^\/\\\\]+)$/);
    var strName = objRE.exec(strFilepath);
                 
    if (strName == null) {
        return null;
    }
    else {
        return strName[0];
    }
}
