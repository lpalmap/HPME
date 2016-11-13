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
    const USUARIO_DEPARTAMENTO_QUERY="SELECT distinct s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s,seg_usuario_rol ur,seg_rol rol WHERE ur.ide_usuario=s.ide_usuario and ur.ide_rol=rol.ide_rol and rol.nombre=:usuarioRol and NOT EXISTS (SELECT d.ide_usuario_director FROM cfg_departamento d WHERE d.ide_usuario_director=s.ide_usuario) order by s.usuario";
    
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
    
    //Presupuesto
    const PLN_PROYECTO_PRESUPUESTO_POR_PLANIFICACION="SELECT p.ide_proyecto_presupuesto FROM pln_proyecto_presupuesto p WHERE p.ide_proyecto_planificacion=:ideProyecto";
    
    const PLN_PROYECTO_PRESUPUESTO_POR_DEPARTAMENTO="SELECT p.ide_presupuesto_departamento FROM pln_presupuesto_departamento p WHERE p.ide_proyecto_presupuesto=:ideProyectoPresupuesto and p.ide_departamento=:ideDepartamento";
    const PLN_DEPARTAMENTO_POR_USUARIO="SELECT d.ide_departamento FROM cfg_departamento d WHERE d.ide_usuario_director=:ideUsuario";    
    const PLN_PRESUPUESTO_POR_DEPARTAMENTO="SELECT p.ide_presupuesto_departamento,d.nombre,p.fecha_ingreso,p.fecha_aprobacion,p.estado FROM pln_presupuesto_departamento p,cfg_departamento d WHERE p.ide_departamento=d.ide_departamento AND p.ide_proyecto_presupuesto=:ideProyectoPresupuesto AND d.ide_departamento=:ideDepartamento";
    const PLN_PRESUPUESTO_COLABORADOR_DEPARTAMENTO="SELECT p.ide_presupuesto_colaborador,p.fecha_ingreso,c.nombres,c.apellidos FROM pln_presupuesto_colaborador p,cfg_colaborador c where p.ide_colaborador=c.ide_colaborador and p.ide_presupuesto_departamento=:idePresupuestoDepartamento";
    const PLN_PRESUPUESTO_COLABORADORES_DEPARTAMENTO="SELECT c.ide_colaborador,c.nombres,c.apellidos FROM cfg_colaborador c WHERE c.ide_departamento=:ideDepartamento AND NOT EXISTS(SELECT p.ide_colaborador FROM pln_presupuesto_colaborador p where p.ide_colaborador=c.ide_colaborador AND p.ide_presupuesto_departamento=:idePresupuestoDepartamento)";
   //cuentas
    const CFG_CUENTAS_PARENT="SELECT T2.ide_cuenta, T2.nombre FROM (SELECT @r AS _id, (SELECT @r := ide_cuenta_padre FROM cfg_cuenta WHERE ide_cuenta = _id) AS ide_cuenta_padre, @l := @l + 1 AS lvl FROM (SELECT @r := :ideCuenta, @l := 0) vars, cfg_cuenta m WHERE @r <> 0) T1 JOIN cfg_cuenta T2 ON T1._id = T2.ide_cuenta ORDER BY T1.lvl DESC";
    

    const SI='S';
    const NO='N';
    const ABIERTO='ABIERTO';
    const CERRADO='CERRADO';
    const PUBLICADO='PUBLICADO';
    const EJECUTADO='EJECUTADO';
    const DATE_FORMAT='Y-m-d';
    const HTTP_AJAX_ERROR=404;
    
}
