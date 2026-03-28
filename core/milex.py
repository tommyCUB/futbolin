

import lex
import re


#  ---------------------------------------------------------------
#  TOKEN LIST
#  ---------------------------------------------------------------

tokens = (
    # Reserved words
    'ELSE',
    'FOR',
    'IF',

    'WHILE',
    'BREAK',
    'SQRT',
    'SIN',
    'COS',
    'TAN',
    'MINE',
    'YOUR',
    'GOTO',
    'HIT',
    'SHOOT',
    'PASS',
    'FUN',
    'RETURN',
    'DO',
    'FOREACH',
    'AS',
    'FALSE',
    'TRUE',



    # Special characters
    'COMMA',
    'COLON',
    'SEMICOLON',
    'LPAREN',
    'RPAREN',
    'LBRACKET',
    'RBRACKET',
    'LBRACE',
    'RBRACE',
    'ASSIGN',
    'GREATER',
    'LESS',
    'EQ',
    'NOT_EQ',
    'GREATER_EQ',
    'LESS_EQ',
    'DOUBLE_PLUS',
    'DOUBLE_MINUS',
    'PLUS',
    'MINUS',
    'TIMES',
    'DIV',
    'MODULO',
    'DOT',
    'LAND',
    'LOR',
    'GATO',


    'SHIFT_LEFT',
    'SHIFT_RIGHT',
    'EQ_PLUS',
    'EQ_MINUS',
    'EQ_TIMES',
    'EQ_DIV',
    'EQ_MODULO',

    # Complex tokens
    'ID',
    'FNUMBER',
    'INUMBER',
    'STRING',
    'CHARACTER',
    )

#  ---------------------------------------------------------------
#  RESERVED WORDS
#  ---------------------------------------------------------------

reserved_words = {

    'else' : 'ELSE',
    'for' : 'FOR',
    'foreach' : 'FOREACH',
    'if' : 'IF',
    'as' : 'AS',


    'while' : 'WHILE',
    'do' : 'DO',
    'goto' : 'GOTO',
    'hit':'HIT',
    'shoot':'SHOOT',
    'pass':'PASS',
    'break' : 'BREAK',
    'fun' : 'FUN',
    'return' : 'RETURN',
    'false' : 'FALSE',
    'true' : 'TRUE'

}

#  ---------------------------------------------------------------
#  SPECIAL CHARACTERS
#  ---------------------------------------------------------------

t_COMMA = r','
t_COLON = r':'
t_SEMICOLON = r';'
t_LPAREN = r'\('
t_RPAREN = r'\)'
t_LBRACKET = r'\['
t_RBRACKET = r'\]'
t_LBRACE = r'{'
t_RBRACE = r'}'
t_ASSIGN = r'='

t_GREATER = r'>'
t_LESS = r'<'
t_EQ = r'=='
t_NOT_EQ = r'!='
t_GREATER_EQ = r'>='
t_LESS_EQ = r'<='
t_DOUBLE_PLUS = r'\+\+'
t_DOUBLE_MINUS = r'--'
t_PLUS = r'\+'
t_MINUS = r'-'
t_TIMES = r'\*'
t_DIV = r'/(?!\*)'
t_MODULO = r'\%'

t_DOT = r'\.'
t_EQ_PLUS = r'\+='
t_EQ_MINUS = r'-='
t_EQ_TIMES = r'\*='
t_EQ_DIV = r'/='
t_EQ_MODULO = r'%='
t_LAND = r'&&'
t_LOR = r'\|\|'
t_GATO=r'\#'





#  ---------------------------------------------------------------
#  COMPLEX TOKENS
#  ---------------------------------------------------------------

def t_ID(t):
    r'[A-Za-z_][\w]*'
    if reserved_words.has_key(t.value):
        t.type = reserved_words[t.value]
    return t

def t_FNUMBER(t):
    r'((0(?!\d))|([1-9]\d*))((\.\d+(e[+-]?\d+)?)|(e[+-]?\d+))'
    return t

def t_malformed_fnumber(t):
    r'(0\d+)((\.\d+(e[+-]?\d+)?)|(e[+-]?\d+))'
    print "Line %d. Malformed floating point number '%s'" % (t.lineno, t.value)

def t_INUMBER(t):
    r'0(?!\d)|([1-9]\d*)'
    return t

def t_malformed_inumber(t):
    r'0\d+'
    print "Line %d. Malformed integer '%s'" % (t.lineno, t.value)

def t_CHARACTER(t):
    r"'\w'"
    return t

def t_STRING(t):
    r'"[^\n]*?(?<!\\)"'
    temp_str = t.value.replace(r'\\', '')
    m = re.search(r'\\[^n"]', temp_str)
    if m != None:
        print "Line %d. Unsupported character escape %s in string literal." % (t.lineno, m.group(0))
        return
    return t

#  ---------------------------------------------------------------
#  IGNORED TOKENS
#  ---------------------------------------------------------------

def t_WHITESPACE(t):
    r'[ \t]+'
    pass

def t_NEWLINE(t):
    r'\n+'

    t.lexer.lineno += len(t.value)

def t_COMMENT(t):
    r'/\*[\w\W]*?\*/'
    t.lineno += t.value.count('\n')
    pass

#  ---------------------------------------------------------------
#  ERROR HANDLING
#  ---------------------------------------------------------------

def t_error(t):
    print "Line %d." % (t.lineno,) + "",
    if t.value[0] == '"':
        print "Unterminated string literal."
        if t.value.count('\n') > 0:
            t.skip(t.value.index('\n'))
    elif t.value[0:2] == '/*':
        print "Unterminated comment."
    else:
        print "Illegal character '%s'" % t.value[0]
        t.lexer.skip(1)

lex.lex(debug=0)