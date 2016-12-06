<?php include ('../view/includes/_header.php'); ?>

<h1>Cadastrar Excursão</h1>
    <div class="container">
        <div class="row">
            <form class="col s12 m8 offset-m2" action="../model/model.php" method="post">
                <input type="hidden" name="tipo" value="cadastrarExcursao">

                <div class="row col s12"></div>

                <div class="row col s12">
                    <h5>Qual vai ser o evento?</h5>
                </div>

                <div class="row input-field col s12">
                    <input id="local" name="nome_evento" type="text" class="validate">
                    <label for="local">Nome do Local/Evento</label>
                </div>

                <div class="row input-field col s12 m4">
                    <input id="data" name="data_evento" type="text" class="validate">
                    <label for="data">Data e Hora</label>
                </div>

                <div class="row input-field col s12 m4">
                    <input id="cidade" name="cidade_evento" type="text" class="validate">
                    <label for="cidade">Cidade</label>
                </div>

                <div class="row input-field col s12 m4">
                    <input id="preco" name="valor_evento" type="text" class="validate">
                    <label for="preco">Preço</label>
                </div>
                <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                <div class="row col s12 divider"></div>
                <div class="row col s12 m6">
                    <h5>Vai passar por Onde?</h5>
                </div>

                <div class="col s12 m3 offset-m3">
                    <a class="waves-effect waves-light btn light-blue" id="bt_addParada">Adicionar</a>
                </div>

                <div class="row col s12 hide-on-med-and-up"></div>

                <div id="paradas"></div>
                <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                <div class="row col s12 divider"></div>
                <div class="row col s12 m6">
                    <h5>Quantos Veículos?</h5>
                </div>

                <div class="col s12 m3 offset-m3">
                    <a class="waves-effect waves-light btn light-blue" id="bt_addVeiculo">Adicionar</a>
                </div>

                <div class="row col s12 hide-on-med-and-up"></div>

                <div id="veiculos"></div>
                <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
                <div class="row col s12 divider"></div>
                <div class="input-field col s12">
                    <textarea id="obs_evento" name="obs_evento" class="materialize-textarea" length="120"></textarea>
                    <label for="obs">Observações</label>
                </div>

                <div class="row col s12"></div>

                <div class="row col s12">
                    <div class="col s12 m3">
                        <a class="waves-effect waves-light btn green" onclick="$('form').submit()">Criar</a>
                    </div>

                    <div class="row col s12 hide-on-med-and-up"></div>

                    <div class="col s12 m3 offset-m6">
                        <a class="waves-effect waves-light btn red" href="principal.html">Cancelar</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        $('#obs').characterCounter();

        $('#bt_addParada').click(addParada);
        $('#bt_addVeiculo').click(addVeiculo);

        qtdVeiculos = 0;
        function addVeiculo(){
            var cod = qtdVeiculos++;
            var linha = $('<div class="col s12" id="veiculo_'+cod+'">');
            var card = $('<div class="card grey lighten-3">');
            var content = $('<div class="row card-content">');
            var campoDescricao = $('<div class="input-field col s12"><input id="desc_'+cod+'" name="desc[]" type="text" class="validate"><label for="desc_'+cod+'">DescriÃ§Ã£o</label></div>');
            var campoMotorista = $('<div class="input-field col s12 m6"><input id="motorista_'+cod+'" name="motorista[]" type="text" class="validate"><label for="motorista_'+cod+'">Motorista</label></div>');
            var campoQtdLugares = $('<div class="input-field col s12 m6"><input id="qtdLugares_'+cod+'" name="qtdLugares[]" type="text" class="validate"><label for="qtdLugares_'+cod+'">Qtd Lugares</label></div>');
            var remover = $('<div class="col s12 m3 offset-m9"><a class="waves-effect waves-light btn red">Remover</a></div>');

            content.append(campoDescricao);
            content.append(campoMotorista);
            content.append(campoQtdLugares);
            content.append(remover);

            remover.click(function(){
                $('#veiculo_'+cod).remove();
            });

            if(cod == 0){
                $('#veiculos').append(linha.append(card.append(content)));
            }else{
                $('#veiculos').first().prepend(linha.append(card.append(content)));
            }
        }

        qtdParadas = 0;
        function addParada(){
            var cod = qtdParadas++;
            var content = $('<div class="row card-content">');
            var linha = $('<div class="col s12" id="parada_'+cod+'">');
            var card = $('<div class="card grey lighten-3">');

            var campoLocal = $('<div class="input-field col s12"><input id="local_'+cod+'" name="local[]" type="text" class="validate"><label for="local_'+cod+'">Local</label></div>');
            var campoCidade = $('<div class="input-field col s12 m4"><input id="cidade_'+cod+'" name="cidade[]" type="text" class="validate"><label for="cidade_'+cod+'">Cidade</label></div>');
            var campoData = $('<div class="input-field col s12 m4"><input id="data_'+cod+'" name="data[]" type="text" class="validate"><label for="data">Data e Hora</label></div>');
            var radioIda = $('<div class="col s6 m2"><input class="with-gap" name="idaVolta[]" value="1" type="radio" id="ida_'+cod+'" /><label for="ida_'+cod+'">Ida</label></div>');
            var radioVolta = $('<div class="col s6 m2"><input class="with-gap" name="idaVolta[]" value="0" type="radio" id="volta_'+cod+'" /><label for="volta_'+cod+'">Volta</label></div>');
            var campoObs = $('<div class="input-field col s12"><input id="obs_'+cod+'" name="obs[]" type="text" class="validate"><label for="obs_'+cod+'">Obs</label></div>');
            var remover = $('<div class="col s12 m3 offset-m9"><a class="waves-effect waves-light btn red">Remover</a></div>');

            content.append(campoLocal);
            content.append(campoData);
            content.append(campoCidade);
            content.append(radioIda);
            content.append(radioVolta);
            content.append(campoObs);
            content.append(remover);

            remover.click(function(){
                $('#parada_'+cod).remove();
            });

            if(cod == 0){
                $('#paradas').append(linha.append(card.append(content)));
            }else{
                $('#paradas').first().prepend(linha.append(card.append(content)));
            }
        }
    </script>
<?php include ('../view/includes/_footer.php'); ?>