<?php
namespace App;

class HPMEConstants
{
    //Planificacion querys 
    const META_PROYECTO_QUERY="select m.ide_meta,m.nombre from cfg_meta m where not EXISTS (SELECT p.ide_meta from pln_proyecto_meta p where p.ide_proyecto=:ideProyecto and p.ide_meta=m.ide_meta)";
    const OBJETIVO_META_QUERY="SELECT o.ide_objetivo,o.nombre FROM cfg_objetivo o WHERE NOT EXISTS (SELECT m.ide_objetivo FROM pln_objetivo_meta m WHERE m.ide_objetivo=o.ide_objetivo and m.ide_proyecto=:ideProyecto)";
    const AREA_OBJETIVO_QUERY="SELECT a.ide_area,a.nombre FROM cfg_area_atencion a WHERE NOT EXISTS(SELECT o.ide_area FROM pln_area_objetivo o WHERE o.ide_area=a.ide_area AND o.ide_proyecto=:ideProyecto)";
    const INDICADOR_AREA_QUERY="SELECT i.ide_indicador,i.nombre FROM cfg_indicador i WHERE NOT EXISTS(SELECT a.ide_indicador FROM pln_indicador_area a WHERE a.ide_indicador=i.ide_indicador and a.ide_proyecto=:ideProyecto)";
    const PRODUCTO_INDICADOR_QUERY="SELECT p.ide_producto,p.nombre FROM cfg_producto p WHERE NOT EXISTS(SELECT i.ide_proyecto FROM pln_producto_indicador i where i.ide_producto=p.ide_producto and i.ide_proyecto=:ideProyecto)";
    const USUARIO_REGION_QUERY="SELECT s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s WHERE NOT EXISTS (SELECT u.ide_usuario FROM seg_usuario_region u WHERE u.ide_usuario=s.ide_usuario) order by s.usuario";
    //const USUARIO_REGION_QUERY_EDIT="SELECT s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s WHERE NOT EXISTS (SELECT u.ide_usuario FROM seg_usuario_region u WHERE (u.ide_usuario=s.ide_usuario or s.ide_usuario=:ideUsuario)) order by s.usuario";
    const NOMBRE_ROL_POR_USUARIO="select r.nombre from seg_usuario_rol ur,seg_rol r where ur.ide_usuario=:ideUsuario and r.ide_rol=ur.ide_rol";
    const REGIONES_PROYECTO_QUERY="SELECT r.ide_region,r.nombre FROM cfg_region r WHERE NOT EXISTS(SELECT pr.ide_region FROM cfg_proyecto_region pr where pr.ide_region=r.ide_region AND pr.ide_proyecto=:ideProyecto)";
    const REGION_USUARIO_ADMINISTRADOR_QUERY=" SELECT ide_region FROM seg_usuario_region WHERE ide_usuario=:ideUsuario";
    const ULTIMO_PROYECTO_ABIERTO_QUERY="SELECT p.ide_proyecto FROM pln_proyecto_planificacion p WHERE p.estado=:estado";
    const USUARIO_DEPARTAMENTO_QUERY="SELECT distinct s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s,seg_usuario_rol ur,seg_rol rol WHERE ur.ide_usuario=s.ide_usuario and ur.ide_rol=rol.ide_rol and NOT EXISTS (SELECT d.ide_usuario_director FROM cfg_departamento d WHERE d.ide_usuario_director=s.ide_usuario) order by s.usuario";
    
    //Querys reportes de planificacion
    const PROYECTOS_REGION_QUERY="SELECT p.ide_proyecto_region,r.nombre,u.usuario,DATE_FORMAT(p.fecha_ingreso,'%d-%m-%Y') as fecha_ingreso,DATE_FORMAT(p.fecha_aprobacion,'%d-%m-%Y') as fecha_aprobacion,p.estado FROM pln_proyecto_region p,cfg_region r,seg_usuario u WHERE p.ide_region=r.ide_region and p.ide_usuario_creacion=u.ide_usuario and p.ide_proyecto_planificacion=:ideProyecto ORDER BY r.nombre asc";
    const PLN_METAS_POR_PROYECTO="SELECT p.ide_proyecto_meta,m.nombre FROM pln_proyecto_meta p,cfg_meta m WHERE p.ide_proyecto=:ideProyecto and p.ide_meta=m.ide_meta";
    const PLN_OBJETIVOS_POR_META="SELECT p.ide_objetivo_meta,o.nombre FROM pln_objetivo_meta p,cfg_objetivo o WHERE o.ide_objetivo=p.ide_objetivo and p.ide_proyecto_meta=:ideProyectoMeta ORDER BY o.nombre";
    const PLN_AREAS_POR_OBJETIVO="SELECT p.ide_area_objetivo,a.nombre FROM pln_area_objetivo p,cfg_area_atencion a where a.ide_area=p.ide_area and p.ide_objetivo_meta=:ideObjetivoMeta ORDER BY a.nombre";
    const PLN_INDICADORES_POR_AREA="SELECT p.ide_indicador_area,i.nombre FROM pln_indicador_area p,cfg_indicador i WHERE i.ide_indicador=p.ide_indicador AND p.ide_area_objetivo=:ideAreaObjetivo ORDER BY i.nombre";
    const PLN_PRODUCTOS_POR_INDICADOR="SELECT p.ide_producto_indicador,c.nombre  FROM pln_producto_indicador p,cfg_producto c WHERE c.ide_producto=p.ide_producto AND p.ide_indicador_area=:ideIndicadorArea ORDER BY c.nombre ";
    const PLN_REGION_PRODUCTO="SELECT r.ide_region_producto,p.nombre as proyecto,r.descripcion FROM pln_region_producto r,cfg_proyecto p WHERE r.ide_proyecto=p.ide_proyecto AND r.ide_proyecto_region=:ideProyectoRegion AND r.ide_producto_indicador=:ideProductoIndicador";    
    const PLN_DETALLE_POR_PRODUCTO_REGION="SELECT d.num_detalle,sum(d.valor) as valor FROM pln_region_producto r,pln_region_producto_detalle d WHERE d.ide_region_producto=r.ide_region_producto and r.ide_region_producto=:ideRegionProducto GROUP BY d.num_detalle ORDER BY d.num_detalle";
    const PLN_CONSOLIDADO_POR_PRODUCTO="SELECT d.num_detalle,sum(d.valor) as valor FROM pln_region_producto r,pln_region_producto_detalle d WHERE d.ide_region_producto=r.ide_region_producto and r.ide_producto_indicador=:ideProductoIndicador GROUP BY d.num_detalle ORDER BY d.num_detalle";
    
    //Query validaciones planificacion
    const PLN_PRODUCTOS_COMPLETADOS="SELECT rp.ide_producto_indicador FROM pln_producto_indicador p,pln_region_producto rp,pln_proyecto_region r where p.ide_producto_indicador=rp.ide_producto_indicador and rp.ide_proyecto_region=r.ide_proyecto_region and p.ide_indicador_area=:ideIndicadorArea and r.ide_region=:ideRegion";
    const PLN_METAS_NO_INGRESADAS="SELECT distinct m.nombre FROM pln_producto_indicador p,pln_proyecto_meta pm,cfg_meta m WHERE pm.ide_proyecto=p.ide_proyecto AND pm.ide_meta=p.ide_meta AND pm.ind_obligatorio='S' AND p.ide_meta=m.ide_meta AND NOT EXISTS(SELECT r.ide_region_producto from pln_region_producto r WHERE r.ide_producto_indicador=p.ide_producto_indicador AND r.ide_proyecto_region=:ideProyectoRegion)";
    
    
    //Presupuesto
    const PLN_PROYECTO_PRESUPUESTO_POR_PLANIFICACION="SELECT p.ide_proyecto_presupuesto FROM pln_proyecto_presupuesto p WHERE p.ide_proyecto_planificacion=:ideProyecto";
    
    const PLN_PROYECTO_PRESUPUESTO_POR_DEPARTAMENTO="SELECT p.ide_presupuesto_departamento FROM pln_presupuesto_departamento p WHERE p.ide_proyecto_presupuesto=:ideProyectoPresupuesto and p.ide_departamento=:ideDepartamento";
    const PLN_DEPARTAMENTO_POR_USUARIO="SELECT d.ide_departamento FROM cfg_departamento d WHERE d.ide_usuario_director=:ideUsuario";    
    const PLN_PRESUPUESTO_POR_DEPARTAMENTO="SELECT p.ide_presupuesto_departamento,d.nombre,p.fecha_ingreso,p.fecha_aprobacion,p.estado FROM pln_presupuesto_departamento p,cfg_departamento d WHERE p.ide_departamento=d.ide_departamento AND p.ide_proyecto_presupuesto=:ideProyectoPresupuesto AND d.ide_departamento=:ideDepartamento";
    const PLN_PRESUPUESTO_COLABORADOR_DEPARTAMENTO="SELECT p.ide_presupuesto_colaborador,p.fecha_ingreso,c.nombres,c.apellidos FROM pln_presupuesto_colaborador p,cfg_colaborador_proyecto c where p.ide_colaborador=c.ide_colaborador and p.ide_presupuesto_departamento=:idePresupuestoDepartamento";
    const PLN_PRESUPUESTO_COLABORADORES_DEPARTAMENTO="SELECT c.ide_colaborador,c.nombres,c.apellidos FROM cfg_colaborador_proyecto c WHERE c.ide_departamento=:ideDepartamento AND NOT EXISTS(SELECT p.ide_colaborador FROM pln_presupuesto_colaborador p where p.ide_colaborador=c.ide_colaborador AND p.ide_presupuesto_departamento=:idePresupuestoDepartamento)";
    const PLN_PRESUPUESTO_DETALLE_COLABORADOR="SELECT c.nombres,c.apellidos,p.ide_presupuesto_departamento,d.ide_proyecto_presupuesto FROM pln_presupuesto_colaborador p,cfg_colaborador_proyecto c,pln_presupuesto_departamento d WHERE p.ide_colaborador=c.ide_colaborador AND p.ide_presupuesto_departamento=d.ide_presupuesto_departamento AND p.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PLN_PRESUPUESTO_CUENTA_COMPLETADA_RAIZ="SELECT c.ide_cuenta FROM pln_colaborador_cuenta p,cfg_cuenta c WHERE c.ide_cuenta=p.ide_cuenta AND c.ide_cuenta_padre IS NULL AND p.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PLN_PRESUPUESTO_CUENTAS_COMPLETADAS="SELECT c.ide_cuenta FROM pln_colaborador_cuenta p,cfg_cuenta c WHERE c.ide_cuenta=p.ide_cuenta AND c.ide_cuenta_padre=:ideCuenta AND p.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PLN_PRESUPUESTO_ESTADO_DEPARTAMENTO_COLABORADOR="SELECT d.estado FROM pln_presupuesto_colaborador c,pln_presupuesto_departamento d WHERE c.ide_presupuesto_departamento=d.ide_presupuesto_departamento and c.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    //cuentas
    const CFG_CUENTAS_PARENT="SELECT T2.ide_cuenta, T2.nombre FROM (SELECT @r AS _id, (SELECT @r := ide_cuenta_padre FROM cfg_cuenta WHERE ide_cuenta = _id) AS ide_cuenta_padre, @l := @l + 1 AS lvl FROM (SELECT @r := :ideCuenta, @l := 0) vars, cfg_cuenta m WHERE @r <> 0) T1 JOIN cfg_cuenta T2 ON T1._id = T2.ide_cuenta ORDER BY T1.lvl DESC";
    const CFG_CUENTAS_PARENT_SOLO_ID="SELECT T2.ide_cuenta FROM (SELECT @r AS _id, (SELECT @r := ide_cuenta_padre FROM cfg_cuenta WHERE ide_cuenta = _id) AS ide_cuenta_padre, @l := @l + 1 AS lvl FROM (SELECT @r := :ideCuenta, @l := 0) vars, cfg_cuenta m WHERE @r <> 0) T1 JOIN cfg_cuenta T2 ON T1._id = T2.ide_cuenta ORDER BY T1.lvl DESC";
    const PLN_CUENTAS_HIJAS_ACTIVAS="SELECT c1.ide_cuenta,c1.cuenta,c1.nombre,c1.descripcion, COUNT(c2.ide_cuenta) hijas FROM cfg_cuenta c1 LEFT JOIN cfg_cuenta c2 ON c1.ide_cuenta = c2.ide_cuenta_padre and c2.estado='ACTIVA' where c1.ide_cuenta_padre=:ideCuentaPadre and c1.estado='ACTIVA'  GROUP BY c1.ide_cuenta";
    const PLN_CUENTAS_HIJAS_CONSOLIDA="SELECT c.ide_cuenta,c.cuenta,c.nombre,c.ind_consolidar FROM cfg_cuenta c WHERE c.ide_cuenta_padre=:ideCuentaPadre ORDER BY c.ide_cuenta asc";
    const PLN_CUENTAS_HIJAS_CONSOLIDA_RAIZ="SELECT c.ide_cuenta,c.cuenta,c.nombre,c.ind_consolidar FROM cfg_cuenta c WHERE c.ide_cuenta_padre is NULL ORDER BY c.ide_cuenta asc";
    const PLN_TOTAL_CUENTA_PARENT="SELECT sum(valor) as total FROM pln_colaborador_cuenta_detalle d,pln_colaborador_cuenta t,cfg_cuenta c WHERE d.ide_colaborador_cuenta=t.ide_colaborador_cuenta AND t.ide_cuenta=c.ide_cuenta AND t.ide_presupuesto_colaborador=:idePresupuestoColaborador AND c.ide_cuenta_padre=:ideCuentaPadre";
    
    const PLN_PRESUPUESTOS_DEPARTAMENTOS="SELECT p.ide_presupuesto_departamento,d.nombre,DATE_FORMAT(p.fecha_ingreso,'%d-%m-%Y') as fecha_ingreso,DATE_FORMAT(p.fecha_aprobacion,'%d-%m-%Y') as fecha_aprobacion,p.estado FROM pln_presupuesto_departamento p,cfg_departamento d WHERE p.ide_departamento=d.ide_departamento and p.ide_proyecto_presupuesto=:ideProyectoPresupuesto ORDER BY d.nombre asc";
    
    //ELIMINAR DATOS
    const PLN_PRESUPUESTO_ELIMINAR_DETALLE_CUENTA_COLABORADOR="DELETE FROM pln_colaborador_cuenta_detalle WHERE ide_colaborador_cuenta in (SELECT ide_colaborador_cuenta FROM pln_colaborador_cuenta c where c.ide_presupuesto_colaborador=:idePresupuestoColaborador)";
    const PLN_PRESUPUESTO_ELIMINAR_CUENTAS_COLABORADOR="DELETE FROM pln_colaborador_cuenta WHERE ide_presupuesto_colaborador=:idePresupuestoColaborador";
    
    //cuenta colaboardor
    const PLN_COLABORADOR_CUENTA="SELECT c.ide_colaborador_cuenta FROM pln_colaborador_cuenta c WHERE c.ide_cuenta=:ideCuenta AND c.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PLN_COLABORADOR_CUENTA_DETALLE="SELECT d.ide_colaborador_cuenta_detalle,num_detalle FROM pln_colaborador_cuenta_detalle d WHERE d.ide_colaborador_cuenta=:ideColaboradorCuenta";
    const PLN_COLABORADOR_CUENTA_DETALLE_VALORES="SELECT d.num_detalle,d.valor FROM pln_colaborador_cuenta_detalle d,pln_colaborador_cuenta c where c.ide_colaborador_cuenta=d.ide_colaborador_cuenta and c.ide_cuenta=:ideCuenta and c.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    
    //
    const PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_PADRE_COLABORADOR="SELECT distinct c.ide_cuenta_padre FROM pln_colaborador_cuenta p,cfg_cuenta c where p.ide_cuenta=c.ide_cuenta AND c.ide_cuenta_padre IS NOT NULL AND p.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_RAIZ_COLABORADOR="SELECT distinct c.ide_cuenta FROM pln_colaborador_cuenta p,cfg_cuenta c where p.ide_cuenta=c.ide_cuenta AND c.ide_cuenta_padre is null AND p.ide_presupuesto_colaborador=:idePresupuestoColaborador";
    const PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_PADRE_DEPARTAMENTO="SELECT distinct c.ide_cuenta_padre FROM pln_colaborador_cuenta p,pln_presupuesto_colaborador l,cfg_cuenta c where p.ide_presupuesto_colaborador=l.ide_presupuesto_colaborador AND p.ide_cuenta=c.ide_cuenta AND c.ide_cuenta_padre IS NOT NULL AND l.ide_presupuesto_departamento=:idePresupuestoDepartamento";
    const PLN_PRESUPUESTO_CONSOLIDADO_CUENTA_RAIZ_DEPARTAMENTO="SELECT distinct c.ide_cuenta FROM pln_colaborador_cuenta p,pln_presupuesto_colaborador l,cfg_cuenta c where p.ide_presupuesto_colaborador=l.ide_presupuesto_colaborador AND p.ide_cuenta=c.ide_cuenta AND c.ide_cuenta_padre is null AND l.ide_presupuesto_departamento=:idePresupuestoDepartamento";
    
    //reporte consolidado
    const CONSOLIDADO_COLABORADOR_CUENTA_PADRE="SELECT 
  c.ide_cuenta,c.cuenta,c.nombre,
  sum(IF(d.num_detalle=1,d.valor,0)) as item1,
  sum(IF(d.num_detalle=2,d.valor,0)) as item2,
  sum(IF(d.num_detalle=3,d.valor,0)) as item3,
  sum(IF(d.num_detalle=4,d.valor,0)) as item4,
  sum(IF(d.num_detalle=5,d.valor,0)) as item5,
  sum(IF(d.num_detalle=6,d.valor,0)) as item6,
  sum(IF(d.num_detalle=7,d.valor,0)) as item7,
  sum(IF(d.num_detalle=8,d.valor,0)) as item8,
  sum(IF(d.num_detalle=9,d.valor,0)) as item9,
  sum(IF(d.num_detalle=10,d.valor,0)) as item10,
  sum(IF(d.num_detalle=11,d.valor,0)) as item11,
  sum(IF(d.num_detalle=12,d.valor,0)) as item12
  from pln_colaborador_cuenta_detalle d,pln_colaborador_cuenta p,cfg_cuenta c 
  where d.ide_colaborador_cuenta=p.ide_colaborador_cuenta and p.ide_cuenta=c.ide_cuenta AND c.ide_cuenta_padre=:ideCuentaPadre AND p.ide_presupuesto_colaborador=:idePresupuestoColaborador GROUP BY c.ide_cuenta,c.cuenta,c.nombre";
    
    const CONSOLIDADO_DEPARTAMENTO_CUENTA_PADRE="SELECT 
  c.ide_cuenta,c.cuenta,c.nombre,
  sum(IF(d.num_detalle=1,d.valor,0)) as item1,
  sum(IF(d.num_detalle=2,d.valor,0)) as item2,
  sum(IF(d.num_detalle=3,d.valor,0)) as item3,
  sum(IF(d.num_detalle=4,d.valor,0)) as item4,
  sum(IF(d.num_detalle=5,d.valor,0)) as item5,
  sum(IF(d.num_detalle=6,d.valor,0)) as item6,
  sum(IF(d.num_detalle=7,d.valor,0)) as item7,
  sum(IF(d.num_detalle=8,d.valor,0)) as item8,
  sum(IF(d.num_detalle=9,d.valor,0)) as item9,
  sum(IF(d.num_detalle=10,d.valor,0)) as item10,
  sum(IF(d.num_detalle=11,d.valor,0)) as item11,
  sum(IF(d.num_detalle=12,d.valor,0)) as item12
  from pln_colaborador_cuenta_detalle d,pln_colaborador_cuenta p,pln_presupuesto_colaborador l,cfg_cuenta c 
  where d.ide_colaborador_cuenta=p.ide_colaborador_cuenta and p.ide_presupuesto_colaborador=l.ide_presupuesto_colaborador and p.ide_cuenta=c.ide_cuenta AND c.ide_cuenta_padre=:ideCuentaPadre AND l.ide_presupuesto_departamento=:idePresupuestoDepartamento GROUP BY c.ide_cuenta,c.cuenta,c.nombre";
    
    const SI='S';
    const NO='N';
    const ABIERTO='ABIERTO';
    const CERRADO='CERRADO';
    const APROBADO='APROBADO';
    const ACTIVA='ACTIVA';
    const INACTIVA='INACTIVA';
    const PUBLICADO='PUBLICADO';
    const EJECUTADO='EJECUTADO';
    const ENVIADO='ENVIADO';
    const DATE_FORMAT='Y-m-d';
    const DATETIME_FORMAT='Y-m-d H:i:s';
    const TIME_ZONE='America/Guatemala';
    const HTTP_AJAX_ERROR=404;
    const COLABORADOR='Colaborador';
    const PROYECTO='Proyecto';
    
}
