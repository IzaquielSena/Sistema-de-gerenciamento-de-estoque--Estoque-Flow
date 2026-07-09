<?php 
	class entradas_estoque{
		
		// Inserir entrada de estoque
		public function inserirEntrada($dados){
			$c= new conectar();
			$conexao=$c->conexao();
			$data=date('Y-m-d');

			// $dados[0] = id_produto
			// $dados[1] = id_usuario
			// $dados[2] = quantidade
			// $dados[3] = preco (custo)
			// $dados[4] = data_entrada
			// $dados[5] = preco_venda

			$preco_venda = isset($dados[5]) ? $dados[5] : 0;

			$sql="INSERT into entradas_estoque (id_produto, id_usuario, quantidade, preco, preco_venda, data_entrada, dataCaptura) 
				  values ('$dados[0]', '$dados[1]', '$dados[2]', '$dados[3]', '$preco_venda', '$dados[4]', '$data')";
			
			return mysqli_query($conexao,$sql);
		}

		// Obter todas as entradas de um produto
		public function obterEntradasProduto($idProduto){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT id_entrada, 
						id_produto, 
						quantidade,
						preco,
						preco_venda,
						data_entrada,
						dataCaptura
				from entradas_estoque 
				where id_produto='$idProduto'
				ORDER BY data_entrada DESC";
			
			return mysqli_query($conexao,$sql);
		}

		// Obter dados de uma entrada específica
		public function obterDados($idEntrada){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT id_entrada, 
						id_produto, 
						quantidade,
						preco,
						preco_venda,
						data_entrada
				from entradas_estoque 
				where id_entrada='$idEntrada'";
			$result=mysqli_query($conexao,$sql);

			$mostrar=mysqli_fetch_row($result);

			$dados=array(
					"id_entrada" => $mostrar[0],
					"id_produto" => $mostrar[1],
					"quantidade" => $mostrar[2],
					"preco" => $mostrar[3],
					"preco_venda" => $mostrar[4],
					"data_entrada" => $mostrar[5]
						);

			return $dados;
		}

		// Atualizar entrada de estoque
		public function atualizar($dados){
			$c= new conectar();
			$conexao=$c->conexao();

			// $dados[0] = id_entrada
			// $dados[1] = quantidade
			// $dados[2] = preco (custo)
			// $dados[3] = data_entrada
			// $dados[4] = preco_venda

			$preco_venda = isset($dados[4]) ? $dados[4] : 0;

			$sql="UPDATE entradas_estoque set quantidade='$dados[1]', 
										preco='$dados[2]',
										preco_venda='$preco_venda',
										data_entrada='$dados[3]'
						where id_entrada='$dados[0]'";

			return mysqli_query($conexao,$sql);
		}

		// Excluir entrada de estoque
		public function excluir($idEntrada){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="DELETE from entradas_estoque 
					where id_entrada='$idEntrada'";
			
			return mysqli_query($conexao,$sql);
		}

		// Obter quantidade total em estoque de um produto
			public function obterQuantidadeTotal($idProduto){
				$c= new conectar();
				$conexao=$c->conexao();

				$sql="SELECT COALESCE(SUM(quantidade), 0) as total
						from entradas_estoque 
						where id_produto='$idProduto'";
				
				$result=mysqli_query($conexao,$sql);
				if (!$result) return 0;
				$row=mysqli_fetch_assoc($result);

				return $row['total'] ?? 0;
			}

		// Obter preço de custo atual (última entrada)
			public function obterPrecoAtual($idProduto){
				$c= new conectar();
				$conexao=$c->conexao();

				$sql="SELECT preco
						from entradas_estoque 
						where id_produto='$idProduto'
						ORDER BY data_entrada DESC
						LIMIT 1";
				
				$result=mysqli_query($conexao,$sql);
				if (!$result) return 0;
				$row=mysqli_fetch_assoc($result);

				return $row ? $row['preco'] : 0;
			}

		// Obter preço de venda atual (última entrada)
			public function obterPrecoVendaAtual($idProduto){
				$c= new conectar();
				$conexao=$c->conexao();

				$sql="SELECT preco_venda
						from entradas_estoque 
						where id_produto='$idProduto' AND preco_venda > 0
						ORDER BY data_entrada DESC
						LIMIT 1";
				
				$result=mysqli_query($conexao,$sql);
				if (!$result) return 0;
				$row=mysqli_fetch_assoc($result);

				return $row ? $row['preco_venda'] : 0;
			}

		// Listar todas as entradas de estoque com informações do produto
		public function listarTodasEntradas(){
			$c= new conectar();
			$conexao=$c->conexao();

			$sql="SELECT e.id_entrada,
						e.id_produto,
						p.nome,
						e.quantidade,
						e.preco,
						e.preco_venda,
						e.data_entrada,
						e.dataCaptura
					from entradas_estoque e
					INNER JOIN produtos p ON e.id_produto = p.id_produto
					WHERE e.quantidade > 0
					ORDER BY e.data_entrada DESC";
			
			return mysqli_query($conexao,$sql);
		}
	}

 ?>
