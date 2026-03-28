# -*- coding: utf-8 -*-
import yacc
import milex
from lex import LexToken

#-------------------------------------------------------------------------------
precedence = (
    ('right', 'ELSE'),
    ('left','PLUS','MINUS'),
    ('left','TIMES','DIV','MODULO'),
)

tokens = milex.tokens

ci="code"

def p_inicio_01(t):
    '''inicio : body

              '''
    t[0]= t[1]

def p_inicio_02(t):
    '''inicio : body inicio
              '''
    t[0]= t[1] + t[2]

def p_body(t):
    '''body : cond
            | inst SEMICOLON
            | loop
            | decl_function
            '''
    t[0]=(t[1],)




def p_decl_function(t):
    '''decl_function : FUN ID LPAREN lista_id RPAREN LBRACE body_list RBRACE
                    '''
    t[0]=('DECLFUNC',t[2],t[4],t[7],t.lineno(1))



def p_lista_id_01(t):
    '''lista_id : ID COMMA lista_id
        '''

    t[0]=(('ID',t[1]),t.lineno(1),) + t[3]

def p_lista_id_02(t):
    '''lista_id : ID
        '''
    t[0]=(('ID',t[1],t.lineno(1),),)

def p_lista_id_03(t):
    '''lista_id : empty
        '''
    pass


def p_cond_01(t):
    '''cond : IF LPAREN comparative RPAREN llaves
                  '''
    t[0]=("IF",t[3],t[5],t.lineno(1))

def p_cond_02(t):
    '''cond : IF LPAREN comparative RPAREN llaves ELSE llaves
                  '''
    t[0]=("IFELSE",t[3],t[5],t[7],t.lineno(1))


def p_llaves_01(t):
    '''llaves : LBRACE body_list RBRACE
    '''
    t[0]= t[2]

def p_llaves_02(t):
    '''llaves : inst SEMICOLON
    '''
    t[0]= (t[1],)

def p_loop_01(t):
    '''loop : FOR LPAREN inst SEMICOLON comparative SEMICOLON exp RPAREN llaves
            '''
    t[0]=("FOR",t[3],t[5],t[7],t[9],t.lineno(1))


def p_loop_03(t):
    '''loop : WHILE LPAREN comparative RPAREN llaves
            '''
    t[0]=("WHILE",t[3],t[5],t.lineno(1))

def p_loop_04(t):
    '''loop : DO llaves WHILE LPAREN comparative RPAREN SEMICOLON
            '''
    t[0]=("DOWHILE",t[2],t[5],t.lineno(1))

def p_loop_05(t):
    '''loop : FOREACH LPAREN ID AS ID RPAREN llaves
            '''
    t[0]=("FOREACH",t[3],t[5],t[7],t.lineno(1))

def p_comparative_01(t):
    '''comparative : unary_comp'''
    t[0]=t[1]

def p_comparative_02(t):
    '''comparative : comparative op lista_comp'''
    t[0]= ("COMP",t[1],t[2],t[3],t.lineno(1))

def p_comparative_03(t):
    '''comparative : LPAREN comparative RPAREN'''
    t[0]=t[2]


def p_lista_comp_01(t):
    '''lista_comp : comparative lista_comp
                  '''
    t[0]=t[1]

def p_lista_comp_02(t):
    '''lista_comp : empty
                  '''
    pass

def p_op(t):
    '''op : LOR
          | LAND'''
    t[0]=t[1]


def p_inst_01(t):
    '''inst : asig
            | orden
            | funcall
            | break'''
    t[0]=t[1]

def p_inst_02(t):
    '''inst : RETURN exp'''
    t[0]=('RETURNED',t[2],t.lineno(1))

def p_inst_03(t):
    '''inst : RETURN '''
    t[0]=('RETURNED',t[2],t.lineno(1))

def p_break_01(t):
    '''break : BREAK
            '''
    t[0]=("BREAK",)

def p_recp_01(t):
    '''recp : ID
            '''
    t[0]=('ID',t[1],t.lineno(1))

def p_recp_02(t):
    '''recp : ID LBRACKET exp RBRACKET
            '''
    t[0]=("ARRAY",t[1],t[3],t.lineno(1))

def p_asig(t):
    '''asig : recp ASSIGN exp
           | recp EQ_PLUS exp
           | recp EQ_MINUS exp
           | recp EQ_TIMES exp
           | recp EQ_DIV exp
           | recp EQ_MODULO exp
           '''
    t[0]=("ASSIGN",t[1],t[2],t[3],t.lineno(1))

def p_exp_01(t):
    '''exp : exp_unary
           '''
    t[0]=t[1]

def p_exp_02(t):
    '''exp :  LPAREN exp RPAREN
           '''
    t[0]=t[2]

def p_exp_03(t):
    '''exp :  exp PLUS exp
           | exp MINUS exp
           | exp TIMES exp
           | exp DIV exp
           | exp MODULO exp
           '''
    t[0]=('BINOP',t[1],t[2],t[3],t.lineno(1))

def p_exp_05(t):
    '''exp : DOUBLE_PLUS exp_unary
            '''
    t[0]=("PLUSPLUS",t[2],t.lineno(1))


def p_exp_06(t):
    '''exp :  exp_unary DOUBLE_PLUS
            '''
    t[0]=("PLUSPLUS",t[1],t.lineno(1))

def p_exp_07(t):
    '''exp :  exp_unary DOUBLE_MINUS
            '''
    t[0]=("MINUSMINUS",t[1],t.lineno(1))

def p_exp_08(t):
    '''exp :   DOUBLE_MINUS exp_unary
            '''
    t[0]=("MINUSMINUS",t[2],t.lineno(1))

def p_exp_09(t):
    '''exp : ID LBRACKET exp RBRACKET
            '''
    t[0]=("ARRAY",t[1],t[3],t.lineno(1))

def p_exp_10(t):
    '''exp : funcall'''
    t[0]= t[1]

def p_funcall(t):
    '''funcall : ID LPAREN lista_atrib RPAREN'''
    t[0]= ("FUNCCALL",t[1],t[3],t.lineno(1))

def p_lista_atrib_01(t):
    '''lista_atrib : exp COMMA lista_atrib
                '''
    t[0]=(t[1],)+t[3]

def p_lista_atrib_02(t):
    '''lista_atrib : exp
                '''
    t[0]=(t[1],)

def p_lista_atrib_03(t):
    '''lista_atrib : empty
                '''
    pass

def p_exp_unary_01(t):
    '''exp_unary : INUMBER
                 '''
    t[0]=('INT',t[1],t.lineno(1))

def p_exp_unary_02(t):
    '''exp_unary : FNUMBER
                '''
    t[0]=('FLOAT',t[1],t.lineno(1))

def p_exp_unary_03(t):
    '''exp_unary : ID
                '''
    t[0]=('ID',t[1],t.lineno(1))

def p_exp_unary_04(t):
    '''exp_unary : LPAREN exp RPAREN
                '''
    t[0]=t[2]

def p_exp_unary_04(t):
    '''exp_unary : MINUS exp_unary
                '''
    t[0]=("MINUS",t[2])

def p_exp_unary_05(t):
    '''exp_unary : TRUE
                | FALSE
                '''
    t[0]=("BOLEAN",t[1])

def p_body_list_01(t):
    '''body_list : body'''
    t[0]= t[1]

def p_body_list_02(t):
    '''body_list : body body_list'''
    t[0]=t[1] + t[2]

def p_body_list_03(t):
    '''body_list : empty'''
    pass


def p_unary_comp_01(t):
    '''unary_comp : exp LESS exp
                 | exp GREATER exp
                 | exp LESS_EQ exp
                 | exp EQ exp
                 | exp NOT_EQ exp
                 | exp GREATER_EQ exp
                 '''
    t[0]=("COMP",t[1],t[2],t[3],t.lineno(1))

def p_unary_comp_02(t):
    '''unary_comp : exp
        '''
    t[0]=("COMP",t[1],"@",t[1],t.lineno(1))

def p_empty(t):
    'empty :'
    pass

def p_orden_01(t):
    '''orden : HIT LPAREN lista_atrib RPAREN
            | PASS LPAREN lista_atrib RPAREN
            | GOTO LPAREN lista_atrib RPAREN
            | SHOOT LPAREN lista_atrib RPAREN
            '''
    t[0]=('ORDEN',t[1],t[3],t.lineno(1))

def p_error(t):
    if not t:
        save_errores("Error near EOF")
        tok= LexToken()
        tok.value=';'
        tok.type='SEMICOLON'
        return tok

    save_errores("Error near < "+t.value+"> Line:( "+str(t.lineno)+" )")


    while 1:
        tok = yacc.token()             # Get the next token
        if not tok or tok.type == 'SEMICOLON': break


    # Return SEMI to the parser as the next lookahead token
    return tok


def save_errores(e):
    f = file(str(miparser.ci)+"(error).txt",'a')
    f.write(str(e)+"\n::")
    f.close()

miparser=yacc.yacc(debug=1)

def parse(data,ci, debug=0):
    miparser.ci=ci
    f= file(str(miparser.ci)+"(error).txt",'w')
    f.close()
    miparser.error = 0
    p = miparser.parse(data,debug=debug,tracking=True)

    return p



