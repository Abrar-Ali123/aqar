function getInputElement(e) {
    return document.getElementById("digit" + e + "-input")
}

function moveToNext(e, t) {
    var t = t.which || t.keyCode,
        n = getInputElement(e);
    1 === n.value.length && (4 !== e ? getInputElement(e + 1).focus() : n.blur()), 8 === t && 1 !== e && getInputElement(e - 1).focus()
}