'use client'

import * as React from 'react';
import { ThemeProvider as NextThemesProvider } from 'next-themes';

import type { ThemeProviderProps } from 'next-themes/dist/types';

interface Props extends ThemeProviderProps {
  children: React.ReactNode;
}

export default function ThemeProvider({ children, ...props }: Props) {
  return <NextThemesProvider {...props}>{children}</NextThemesProvider>;
}