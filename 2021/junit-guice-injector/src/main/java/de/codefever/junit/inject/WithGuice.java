/*
 * Copyright (c) 2020 Eric Kubenka.
 * Licensed under the MIT License.
 * See LICENSE.md file in the project root for full license information.
 */
package de.codefever.junit.inject;

import com.google.inject.Module;
import org.junit.jupiter.api.extension.ExtendWith;

import java.lang.annotation.ElementType;
import java.lang.annotation.Retention;
import java.lang.annotation.RetentionPolicy;
import java.lang.annotation.Target;

@ExtendWith(GuiceInjector.class)
@Retention(RetentionPolicy.RUNTIME)
@Target({ElementType.TYPE, ElementType.FIELD, ElementType.PARAMETER, ElementType.METHOD})
public @interface WithGuice {

    Class<? extends Module>[] modules() default {};
}
