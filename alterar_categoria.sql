-- Permite que um produto fique sem categoria
-- (necessário para poder excluir categorias que têm produtos)
ALTER TABLE public.produto ALTER COLUMN categoria_id DROP NOT NULL;

-- Se a tabela cliente também tiver categoria_id, libera também
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM information_schema.columns
               WHERE table_schema = 'public' AND table_name = 'cliente' AND column_name = 'categoria_id') THEN
        ALTER TABLE public.cliente ALTER COLUMN categoria_id DROP NOT NULL;
    END IF;
END $$;
