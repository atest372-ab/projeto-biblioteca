# 📚 Projeto Biblioteca - Inovcorp

Sistema de gestão de biblioteca desenvolvido em Laravel com foco em auditoria e testes automatizados.

## ✅ Estado do Projeto: Fase 6 Concluída (15/04/2026)

Esta fase focou-se na segurança, rastreabilidade e integridade dos dados.

### Funcionalidades Implementadas:
- [x] **Sistema de Auditoria:** Registo completo de logs (Data, Hora, User, Módulo, IP e Browser).
- [x] **Gestão de Inventário:** Controlo de stock dinâmico que impede requisições sem unidades disponíveis.
- [x] **Interface Inteligente:** Dashboard atualizado com feedback de stock e ações de administrador.
- [x] **Testes PEST:** 6 testes de funcionalidade automatizados com 100% de sucesso.

## 🛠️ Como testar no Terminal:
```bash
php artisan test --filter RequisicaoLivroTest