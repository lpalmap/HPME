<?php
namespace App;

class PresupuestoConstants
{
    const PRESUPUESTO_CLONAR_DEPARTAMENTO="select pd.ide_presupuesto_departamento,pp.descripcion,d.nombre from pln_presupuesto_departamento pd,pln_proyecto_presupuesto pp,cfg_departamento d where pd.ide_departamento=d.ide_departamento and pd.ide_proyecto_presupuesto=pp.ide_proyecto_presupuesto and pd.ide_presupuesto_departamento<>:idePresupuestoDepartamento order by pd.ide_presupuesto_departamento desc";   

    //BORRAR PRESUPUESTO DEPARTAMENTO
    const PRESUPUESTO_ELIMINAR_DETALLE_CUENTA_COLABORADOR="DELETE FROM pln_colaborador_cuenta_detalle WHERE ide_colaborador_cuenta in (SELECT ide_colaborador_cuenta FROM pln_colaborador_cuenta c,pln_presupuesto_colaborador pc where c.ide_presupuesto_colaborador=pc.ide_presupuesto_colaborador and pc.ide_presupuesto_departamento=:idePresupuestoDepartamento)";
    const PRESUPUESTO_ELIMINAR_CUENTAS_COLABORADOR="DELETE FROM pln_colaborador_cuenta WHERE ide_presupuesto_colaborador in (SELECT c.ide_presupuesto_colaborador FROM pln_presupuesto_colaborador c WHERE c.ide_presupuesto_departamento=:idePresupuestoDepartamento)";
    const PRESUPUESTO_ELIMINAR_PRESUPUESTO_COLABORADOR="DELETE FROM pln_presupuesto_colaborador WHERE ide_presupuesto_departamento=:idePresupuestoDepartamento";
    //QUERYS PARA CLONAR PRESUPUESTO
    const PRESUPUESTO_COLABORADOR_DEPARTAMENTO="SELECT c.ide_presupuesto_colaborador,c.ide_colaborador FROM pln_presupuesto_colaborador c,cfg_colaborador_proyecto cp,pln_presupuesto_departamento pd where c.ide_colaborador=cp.ide_colaborador AND c.ide_presupuesto_departamento=pd.ide_presupuesto_departamento and cp.ide_departamento=pd.ide_departamento and pd.ide_presupuesto_departamento=:idePresupuestoDepartamento";
    const PRESUPUESTO_COLABORADOR_CUENTA="SELECT cc.ide_colaborador_cuenta,cc.ide_cuenta FROM pln_colaborador_cuenta cc,cfg_cuenta c where cc.ide_cuenta=c.ide_cuenta and c.estado=:estadoCuenta AND cc.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PRESUPUESTO_CUENTA_DETALLE="SELECT d.num_detalle,d.valor FROM pln_colaborador_cuenta_detalle d WHERE d.ide_colaborador_cuenta=:ideColaboradorCuenta";
    const IMPORT_CODIGO_CUENTA=0;
    const IMPORT_PERIODO=1;
    const IMPORT_BASE=2;
    const IMPORT_L1=6;
    const IMPORT_L2=7;
    const IMPORT_L4=8;   
}
