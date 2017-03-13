<?php
namespace App;

class ExportConstants
{
    const PLN_PRESUPUESTO_EXPORT_SUN="SELECT cu.cuenta,cd.num_detalle,cd.valor,de.codigo_interno l1,cu.codigo_interno l2,cp.codigo_interno l4 FROM pln_colaborador_cuenta_detalle cd,pln_colaborador_cuenta cc,cfg_cuenta cu,pln_presupuesto_colaborador pc,cfg_colaborador_proyecto cp,pln_presupuesto_departamento pd,cfg_departamento de WHERE pd.ide_proyecto_presupuesto=:ideProyectoPresupuesto AND cd.ide_colaborador_cuenta=cc.ide_colaborador_cuenta AND cc.ide_cuenta=cu.ide_cuenta AND cc.ide_presupuesto_colaborador=pc.ide_presupuesto_colaborador and pc.ide_colaborador=cp.ide_colaborador AND pc.ide_presupuesto_departamento=pd.ide_presupuesto_departamento AND pd.ide_departamento=de.ide_departamento AND cd.valor>0 limit 10000";
}
