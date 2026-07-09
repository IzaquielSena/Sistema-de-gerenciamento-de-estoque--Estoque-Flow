<?php 
	class produtos{
		public function addImagem($dados){
			$c= new conectar();
			$conexao=$c->conexao();

			$data=date('Y-m-d');

			$sql="INSERT into imagens (id_categoria,
										nome,
										url,
										dataUpload)
							values ('$dados[0]',
									'$dados[1]',
									'$dados[2]',
									'$data')";
			$result=mysqli_query($conexao,$sql);

			return mysqli_insert_id($conexao);
		}

		// Inserir apenas o cadastro do produto (sem quantidade e preço)
		public function inserirProduto($dados){
			$c= new conectar();
			$conexao=$c->conexao();
			$data=date('Y-m-d');

			$sql="INSERT into produtos (id_categoria, id_imagem, id_usuario, nome, descricao, dataCaptura) 
				  values ('$dados[0]', '$dados[1]', '$dados[2]', '$dados[3]', '$dados[4]', '$data')";
			
			return mysqli_query($conexao,$sql);
		}

		public function obterDados($idproduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT id_produto, 
						id_categoria, 
						nome,
						descricao
				from produtos 
				where id_produto='$idproduto'";
			$result=mysqli_query($conexao,$sql);

			$mostrar=mysqli_fetch_row($result);

			$dados=array(
					"id_produto" => $mostrar[0],
					"id_categoria" => $mostrar[1],
					"nome" => $mostrar[2],
					"descricao" => $mostrar[3]
						);

			return $dados;
		}

		public function atualizar($dados){
			$c= new conectar();
			$conexao=$c->conexao();

			// $dados[0] = id_produto
			// $dados[1] = id_categoria
			// $dados[2] = nome
			// $dados[3] = descricao
			// $dados[4] = quantidade (não usado aqui, deve ser via entrada_estoque)
			// $dados[5] = preco (não usado aqui, deve ser via entrada_estoque)

			$sql="UPDATE produtos set id_categoria='$dados[1]', 
										nome='$dados[2]',
										descricao='$dados[3]'
						where id_produto='$dados[0]'";

			return mysqli_query($conexao,$sql);
		}

		public function excluir($idproduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$idimagem=self::obterIdImg($idproduto);

			$sql="DELETE from produtos 
					where id_produto='$idproduto'";
			$result=mysqli_query($conexao,$sql);

			if($result){
				$url=self::obterUrlImagem($idimagem);

				$sql="DELETE from imagens 
						where id_imagem='$idimagem'";
				$result=mysqli_query($conexao,$sql);
					if($result){
						if(unlink($url)){
							return 1;
						}
					}
			}
		}

		public function obterIdImg($idProduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT id_imagem 
					from produtos 
					where id_produto='$idProduto'";
			$result=mysqli_query($conexao,$sql);

			return mysqli_fetch_row($result)[0];
		}

		public function obterUrlImagem($idImg){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT url 
					from imagens 
					where id_imagem='$idImg'";

			$result=mysqli_query($conexao,$sql);

			return mysqli_fetch_row($result)[0];
		}


		// Gera relatório geral de estoque (todos os produtos com quantidade e preço)
		public function gerarRelatorioEstoqueCompleto(){
			$c = new conectar();
			$conexao = $c->conexao();

			$sql = "SELECT p.nome, 
						p.descricao,
						COALESCE(SUM(e.quantidade), 0) as quantidade,
						COALESCE(MAX(e.preco), 0) as preco,
						p.dataCaptura
					FROM produtos p
					LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto
					GROUP BY p.id_produto
					ORDER BY p.nome";

			return mysqli_query($conexao, $sql);
		}

		// Gera relatório de produtos cadastrados em um período de datas
		public function relatorioEstoquePorData($dataInicio, $dataFim){
			$c = new conectar();
			$conexao = $c->conexao();

			$sql = "SELECT p.nome, 
						p.descricao,
						COALESCE(SUM(e.quantidade), 0) as quantidade,
						COALESCE(MAX(e.preco), 0) as preco,
						p.dataCaptura
					FROM produtos p
					LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto
					WHERE p.dataCaptura BETWEEN '$dataInicio' AND '$dataFim'
					GROUP BY p.id_produto
					ORDER BY p.nome";

			return mysqli_query($conexao, $sql);
		}
		// Função para listar todos os produtos cadastrados com estoque e valor
		public function listarProdutosComEstoque(){
			$c = new conectar();
			$conexao = $c->conexao();
			
			$sql = "SELECT p.id_produto, 
						p.nome, 
						p.descricao,
						c.nome_categoria,
						COALESCE(SUM(e.quantidade), 0) as quantidade_estoque,
						COALESCE(e.preco, 0) as preco_atual
					FROM produtos p
					LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
					LEFT JOIN entradas_estoque e ON p.id_produto = e.id_produto
					GROUP BY p.id_produto
					ORDER BY p.nome";
			
			return mysqli_query($conexao, $sql);
		}
	}

 ?>
