using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;

namespace LetsGO.Resources.model
{
    class Excursao
    {
        public string Nome { get; set; }
        public Usuario Usuario { get; set; }
        public List<Usuario> Participantes { get; set; }
        public float Preco { get; set; }
        public int Status { get; set; }

        public Excursao(string nome, Usuario usuario, List<Usuario> participantes, float preco, int status)
        {
            this.Nome = nome;
            this.Usuario = usuario;
            this.Participantes = participantes;
            this.Preco = preco;
            this.Status = status;
        }

        public override string ToString()
        {
            return this.Nome;
        }

    }
}