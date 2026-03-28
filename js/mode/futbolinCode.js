// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: https://codemirror.net/LICENSE

(function(mod) {
  if (typeof exports == "object" && typeof module == "object") // CommonJS
    mod(require("../../js/codemirror"));
  else if (typeof define == "function" && define.amd) // AMD
    define(["../../js/codemirror"], mod);
  else // Plain browser env
    mod(CodeMirror);
})(function(CodeMirror) {
"use strict";

CodeMirror.defineMode("futbolinCode", function() {
  function words(array) {
    var keys = {};
    for (var i = 0; i < array.length; ++i) {
      keys[array[i]] = true;
    }
    return keys;
  }

  var keywords = words([                  
                  "array", "do", "else",  "fun", "if", "none",  "return", "while", "for"]);
	
  var builtins = words(["max", "min","sin","tan", "cos", "sqrt", "distance",
                          "rand", "hisGoals", "amCloserToBall", "yourCloserMine", "myNext", "canHitBall", "myCloserToPoint", "hisCloserToPoint", "push", "pop", "shift", "unshift", "indexOf", "splice", "slice","rand","len"]);

    var dataTypes =  words(["goto","hit", "goto", "pass" ,"shoot","mine","your","ball"]);
	
  var isOperatorChar = /[+\-*&=<>\/\:]/;
  var litOperator = new RegExp("(\.and\.|\.or\.|\.eq\.|\.lt\.|\.le\.|\.gt\.|\.ge\.|\.ne\.|\.not\.|\.eqv\.|\.neqv\.)", "i");

  function tokenBase(stream, state) {

    if (stream.match(litOperator)){
        return 'operator';
    }

    var ch = stream.next();
    if (ch == "!") {
      stream.skipToEnd();
      return "comment";
    }
    if (ch == '"' || ch == "'") {
      state.tokenize = tokenString(ch);
      return state.tokenize(stream, state);
    }
    if (/[\[\]\(\),]/.test(ch)) {
      return null;
    }
    if (/\d/.test(ch)) {
      stream.eatWhile(/[\w\.]/);
      return "number";
    }
    if (isOperatorChar.test(ch)) {
      stream.eatWhile(isOperatorChar);
      return "operator";
    }
    stream.eatWhile(/[\w\$_]/);
    var word = stream.current().toLowerCase();

    if (keywords.hasOwnProperty(word)){
            return 'keyword';
    }
    if (builtins.hasOwnProperty(word) || dataTypes.hasOwnProperty(word)) {
            return 'builtin';
    }
    return "variable";
  }

  function tokenString(quote) {
    return function(stream, state) {
      var escaped = false, next, end = false;
      while ((next = stream.next()) != null) {
        if (next == quote && !escaped) {
            end = true;
            break;
        }
        escaped = !escaped && next == "\\";
      }
      if (end || !escaped) state.tokenize = null;
      return "string";
    };
  }

  // Interface

  return {
    startState: function() {
      return {tokenize: null};
    },

    token: function(stream, state) {
      if (stream.eatSpace()) return null;
      var style = (state.tokenize || tokenBase)(stream, state);
      if (style == "comment" || style == "meta") return style;
      return style;
    }
  };
});

CodeMirror.defineMIME("text/x-futbolinCode",{
    name: "futbolinCode",
    helperType: "futbolinCode"	
});

});
