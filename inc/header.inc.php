<header>
    <span><a href="index.php"> NoDebt </a></span>
			<nav>
				<ul>
					<li><a href="index.php"> Accueil </a></li>
					<li><a href="groupe.php"> Groupes </a></li>
					<li><a href="contactAdmin.php"> Contact</a></li>
				</ul>		
				<details>
					<summary> Profil </summary>
                        <p> <?php if(isset($util)){echo $util->prenom . $util->nom . '</br>' .
                            $util->courriel . '</p>';}?>
                    <div class="lien">
						<a href="editProfil.php">éditer profil</a><br>
                        <a href ="deconnexion.php" > Déconnexion</a>
                    </div>
				</details>
			</nav>
		</header>