<!-- Comienzo Menu Columna lateral izquierda-->
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu">
			<!-- Menu Inicio -->
			<li>
				<a href="inicio">
					<i class="fa fa-home"></i>
					<span>Inicio</span>
				</a>
			</li>
			
			<?php 
		    	if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){
		   			echo '
		   					<!-- Menu Solicitudes -->
		   					<li>
			       				<a href="solicitudes">
			       					<i class="fa fa-user-circle-o"></i>
			       					<span>Solicitudes</span>
			       				</a>
			       			</li>';	
			    }
			    if($_SESSION["perfil"] == "Administrador"){		
			       		echo '
							<!-- Menu Usuarios -->
							<li class="treeview" style="height: auto;">
								<a href="#">
									<i class="fa fa-user"></i>
									<span>Usuarios</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>					
								</a>
								<ul class="treeview-menu">
									<li>
										<a href="vendedores">
											<i class="fa fa-circle-o"></i>
											<span>Vendedores</span>
										</a>
									</li>	
									<li>
										<a href="administradores">
											<i class="fa fa-circle-o"></i>
											<span>Administradores</span>
										</a>
									</li>															
								</ul>
							</li>';  
		     
		     		} 
		     ?>
			
			<!-- Menu Categorias -->
			<?php
				if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){
					echo'
						<li>
							<a href="categorias">
								<i class="fa fa-th"></i>
								<span>Categorias</span>
							</a>
						</li>
					';
				}
			?>
			

			<!-- Menu Productos -->
			<li>
				<a href="productos">
					<i class="fa fa-product-hunt"></i>
					<span>Productos</span>
				</a>
			</li>
			<!-- Menu Clientes -->
			<?php
				if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){
					echo'
						<li>
							<a href="clientes">
								<i class="fa fa-users"></i>
								<span>Clientes</span>
							</a>
						</li>
					';
				}
			?>
			<!-- Menu Locales -->
			<?php 
		    	if($_SESSION["perfil"] == "Administrador" || $_SESSION["perfil"] == "Vendedor"){
		   			echo '
							<li>
								<a href="locales">					
									<i class="fa fa-building-o"></i> 
									<span>Locales</span>
								</a>
							</li>
							<!-- Menu Ventas -->
							<li class="treeview">
								<a href="#">
									<i class="fa fa-list-ul"></i>
									<span>Consumos</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>					
								</a>
								<ul class="treeview-menu">
									<li>
										<a href="adminconsumos">
											<i class="fa fa-circle-o"></i>
											<span>Administrar Consumos</span>
										</a>
									</li>
									<li>
										<a href="crear-consumo">
											<i class="fa fa-circle-o"></i>
											<span>Alta de Consumos</span>
										</a>
									</li>
									<li>
										<a href="reportes">
											<i class="fa fa-circle-o"></i>
											<span>Reporte de Consumos</span>
										</a>
									</li>						
								</ul>
							</li>';  
		     
		     		}
		     	if($_SESSION["perfil"] == "Cliente"){
		   			echo '	
		   					<!-- Menu Consumos -->
							<li>
								<a href="consumos">					
									<i class="fa fa-list-ul"></i> 
									<span>Consumos</span>
								</a>
							</li>';  
		     
		     		}
			?>

		</ul>
	</section>
</aside>
<!-- Fin Menu Columna lateral izquierda-->